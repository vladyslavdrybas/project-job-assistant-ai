<?php
declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\AbstractController;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function check(): void {
        throw new Exception('Should not be achieved.');
    }
}
