<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: "app")]
class CheckPageReplaceController extends AbstractController
{
    #[Route("/replace", name: "_replace", methods: ["GET"])]
    public function index(): Response
    {
        return $this->render(
            'replace/index.html.twig',
        );
    }
}
