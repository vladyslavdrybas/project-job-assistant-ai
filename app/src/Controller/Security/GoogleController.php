<?php
declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\AbstractController;
use App\DataTransferObject\Security\GoogleUserDto;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GoogleUser;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

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
    public function connectAction(
        ClientRegistry $clientRegistry
    ) {
        return $clientRegistry
            ->getClient('google')
            ->redirect(
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
    public function connectCheckAction(
        Request $request,
        ClientRegistry $clientRegistry
    ) {
        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        try {
            // the exact class depends on which provider you're using
            /** @var GoogleUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();

            dump($user->toArray());

            $googleUserDto = $this->serializer
                ->denormalize(
                    $user->toArray(),
                    GoogleUserDto::class
                );

            dump($user);
            dump($googleUserDto);
            die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            dump($e->getMessage()); die;
        }
    }
}
