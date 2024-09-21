<?php
declare(strict_types=1);

namespace App\Controller\Security;

use App\Builder\UserBuilder;
use App\Controller\AbstractController;
use App\DataTransferObject\Security\GoogleUserDto;
use App\Entity\UserGoogle;
use App\Repository\UserGoogleRepository;
use DateTime;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GoogleUser;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route(
    "/security/google",
    name: "security_google"
)]
#[WithMonologChannel('google_oauth')]
class GoogleController extends AbstractController
{
    #[Route(
        "/connect",
        name: "_connect",
        methods: ["GET"]
    )]
    public function request(
        ClientRegistry $clientRegistry
    ): RedirectResponse {
        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        return $client->redirect(
                [
                    'email',
                    'profile',
                    'openid'
                ]
            );
    }

    #[Route(
        "/connect/check",
        name: "_connect_check",
        methods: ["GET"]
    )]
    public function check(
        Request $request,
        ClientRegistry $clientRegistry,
        UserBuilder $userBuilder,
        UserGoogleRepository $userGoogleRepository
    ): void {
        throw new Exception('Should not be achieved.');
//        /** @var GoogleClient $client */
//        $client = $clientRegistry->getClient('google');
//
//        try {
//            $doctrineChanges = false;
//            /** @var GoogleUser $user */
//            $googleUser = $client->fetchUser();
//
//            $googleUserDto = $this->serializer
//                ->denormalize(
//                    $googleUser->toArray(),
//                    GoogleUserDto::class
//                );
//
//            $user = $userBuilder->google($googleUserDto);
//
//            $isUserNew = (new DateTime())->diff($user->getCreatedAt())->s < 5;
//            if ($isUserNew) {
//                $doctrineChanges = true;
//                $userGoogleRepository->add($user);
//            }
//
//            $userGoogle = $userGoogleRepository->findOneBy(['email' => $googleUserDto->email]);
//            if (!$userGoogle instanceof UserGoogle) {
//                $userGoogle = new UserGoogle();
//                $userGoogle->setOwner($user);
//                $userGoogle->setGoogleId($googleUserDto->id);
//                $userGoogle->setEmail($googleUserDto->email);
//                $userGoogle->setFullName($googleUserDto->name);
//                $userGoogle->setFirstName($googleUserDto->firstName);
//                $userGoogle->setLastName($googleUserDto->lastName);
//                $userGoogle->setAvatar($googleUserDto->avatar);
//                $userGoogle->setIsEmailVerified($googleUserDto->isEmailVerified);
//                $userGoogle->setLocale($googleUserDto->locale);
//                $userGoogle->setHostedDomain($googleUserDto->hostedDomain);
//
//                $doctrineChanges = true;
//                $userGoogleRepository->add($userGoogle);
//            }
//
//            if ($doctrineChanges) {
//                $userGoogleRepository->save();
//            }
//            die;
//            // ...
//        } catch (IdentityProviderException $e) {
//            // something went wrong!
//            // probably you should return the reason to the user
//            dump($e->getMessage()); die;
//        }
    }
}
