<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController as BaseController;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/api",
    name: "api",
)]
abstract class AbstractController extends BaseController
{
}
