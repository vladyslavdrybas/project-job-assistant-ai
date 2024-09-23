<?php
declare(strict_types=1);

namespace App\Security\OAuth;

use App\Builder\UserBuilder;
use App\DataTransferObject\Security\OAuth2ResourceOwnerDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseOAuthAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    abstract protected function buildUserFromOAuthDto(OAuth2ResourceOwnerDto $dto): ?User;

    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';
    public const CONNECT_CHECK_ROUTE = 'undefined';
    public const OAUTH_CLIENT = 'undefined';

    public function __construct(
        protected readonly ClientRegistry $clientRegistry,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly UserBuilder $userBuilder,
        protected readonly UrlGeneratorInterface $urlGenerator,
        protected readonly AuthorizationCheckerInterface $authorizationChecker,
        protected readonly UserProviderInterface $userProvider,
        protected readonly SerializerInterface $serializer,
        protected readonly ParameterBagInterface $parameterBag,
        protected readonly Security $security,
    ) {}

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient(static::OAUTH_CLIENT);
        $accessToken = $this->fetchAccessToken($client);

        $userBadge = new UserBadge(
            $accessToken->getToken(),
            function() use ($accessToken, $client) {
                $oAuthProviderResourceOwner = $client->fetchUserFromToken($accessToken);

                $oAuth2resourceOwnerDto = $this->serializer
                    ->denormalize(
                        $oAuthProviderResourceOwner->toArray(),
                        OAuth2ResourceOwnerDto::class
                    );

                return $this->buildUserFromOAuthDto($oAuth2resourceOwnerDto);
            }
        );

        return new SelfValidatingPassport($userBadge);
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === static::CONNECT_CHECK_ROUTE;
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
