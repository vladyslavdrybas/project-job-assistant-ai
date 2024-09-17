<?php
declare(strict_types=1);

namespace App\Controller\AdminPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/ap/dashboard",
    name: "admin_panel_dashboard",
    requirements: [
        'requestCallBack' => RouteRequirements::UUID->value
    ]
)]
class DashboardController extends AbstractAdminPanelController
{
    #[Route(
        '',
        '',
        methods: ['GET']
    )]
    public function index(

    ): ViewResponseDto {
        return $this->response(
            [

            ],
            'admin-panel/dashboard.html.twig'
        );
    }
}
