<?php
declare(strict_types=1);

namespace App\Controller;


use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/job/search",
    name: "job_search"
)]
class JobSearchController extends AbstractController
{
    #[Route(
        path: '',
        name: '',
        methods: ['GET']
    )]
    public function show(): ViewResponseDto {
        return $this->response(
            []
            ,'job/search.html.twig',
        );
    }
}
