<?php

namespace App\Controller;

use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: "security")]
class LoginController extends AbstractController
{
    #[Route(
        path: '/login',
        name: '_login'
    )]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): ViewResponseDto {
        $user = $this->getUser();
        if (null !== $user) {
            return $this->response(
                [
                    'user' => $user->getUsername()
                ],
                'cp_user_show'
            );
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->response(
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ],
            'login/login.html.twig'
        );
    }

    #[Route(
        path: '/logout',
        name: '_logout'
    )]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
