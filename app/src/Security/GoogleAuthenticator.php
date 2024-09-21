<?php
declare(strict_types=1);

namespace App\Security;

use App\Builder\UserBuilder;
use App\DataTransferObject\Security\GoogleUserDto;
use App\Entity\User;
use App\Entity\UserGoogle;
use App\Repository\UserGoogleRepository;
use DateTime;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';

    public function __construct(
        protected readonly ClientRegistry $clientRegistry,
        protected readonly UserGoogleRepository $userGoogleRepository,
        protected readonly UserBuilder $userBuilder,
        protected readonly UrlGeneratorInterface $urlGenerator,
        protected readonly AuthorizationCheckerInterface $authorizationChecker,
        protected readonly UserProviderInterface $userProvider,
        protected SerializerInterface $serializer
    ) {}


    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'security_google_connect_check';
    }

    public function authenticate(Request $request): Passport
    {
        /** @var GoogleClient $client */
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        $userBadge = new UserBadge(
            $accessToken->getToken(),
            function() use ($accessToken, $client) {
                $doctrineChanges = false;
                /** @var GoogleUser $user */
                $googleUser = $client->fetchUserFromToken($accessToken);

                $googleUserDto = $this->serializer
                    ->denormalize(
                        $googleUser->toArray(),
                        GoogleUserDto::class
                    );

                $user = $this->userBuilder->google($googleUserDto);

                $isUserNew = (new DateTime())->diff($user->getCreatedAt())->s < 5;
                if ($isUserNew) {
                    $doctrineChanges = true;
                    $this->userGoogleRepository->add($user);
                }

                $userGoogle = $this->userGoogleRepository->findOneBy(['email' => $googleUserDto->email]);
                if (!$userGoogle instanceof UserGoogle) {
                    $userGoogle = new UserGoogle();
                    $userGoogle->setOwner($user);
                    $userGoogle->setGoogleId($googleUserDto->id);
                    $userGoogle->setEmail($googleUserDto->email);
                    $userGoogle->setFullName($googleUserDto->name);
                    $userGoogle->setFirstName($googleUserDto->firstName);
                    $userGoogle->setLastName($googleUserDto->lastName);
                    $userGoogle->setAvatar($googleUserDto->avatar);
                    $userGoogle->setIsEmailVerified($googleUserDto->isEmailVerified);
                    $userGoogle->setLocale($googleUserDto->locale);
                    $userGoogle->setHostedDomain($googleUserDto->hostedDomain);

                    $doctrineChanges = true;
                    $this->userGoogleRepository->add($userGoogle);
                }

                if ($doctrineChanges) {
                    $this->userGoogleRepository->save();
                }

                return $user;
            }
        );

        return new SelfValidatingPassport($userBadge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        /** @var User $user */
        $user = $token->getUser();

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->urlGenerator->generate('admin_panel_dashboard'));
        }

        return new RedirectResponse($this->urlGenerator->generate('cp_user_show', ['user' => $user->getUsername()]));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            $this->getLoginUrl($request), // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
