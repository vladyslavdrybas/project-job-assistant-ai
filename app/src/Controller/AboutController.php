<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/about",
    name: "app_about"
)]
class AboutController extends AbstractController
{
    #[Route(
        "/",
        name: "_show",
        methods: ["GET"]
    )]
    public function show(): ViewResponseDto
    {

        return $this->response(
            [
            ],
            'about/index.html.twig'
        );
    }
}
