<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app')]
class HomeController extends AbstractController
{
    #[Route(
        '/',
        name: '_homepage',
        methods: ['GET', 'OPTIONS', 'HEAD']
    )]
    public function index(): ViewResponseDto {
        return $this->response(
            [],
            'index.html.twig'
        );
    }
}
