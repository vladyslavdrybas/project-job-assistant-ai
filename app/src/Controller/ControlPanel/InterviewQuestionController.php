<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/interview-question",
    name: "cp_interview_question",
    requirements: [
        'interviewQuestion' => RouteRequirements::UUID->value,
    ]
)]
class InterviewQuestionController extends AbstractControlPanelController
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
                'formActions' => ['add'],
            ],
            'control-panel/interview-question/board.html.twig'
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
            'cp_interview_question_board'
        );
    }

}
