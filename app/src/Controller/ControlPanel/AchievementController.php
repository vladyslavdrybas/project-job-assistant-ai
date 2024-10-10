<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/achievement",
    name: "cp_achievement",
    requirements: [
        'achievement' => RouteRequirements::UUID->value,
    ]
)]
class AchievementController extends AbstractControlPanelController
{
    #[Route(
        's',
        name: '_board',
        methods: ['GET']
    )]
    public function list(
    ): ViewResponseDto {

        return $this->response(
            [
                'skillsFormActions' => ['add'],
            ],
            'control-panel/achievement/board.html.twig'
        );
    }

    #[Route(
        's',
        name: '_add',
        methods: ['POST']
    )]
    public function edit(
        Request $request
    ): ViewResponseDto {
        return $this->response(
            [],
            'cp_achievement_board'
        );
    }
}
