<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Form\CommandCenter\Skill\MySkillsFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/skill",
    name: "cp_skill",
    requirements: [
        'skill' => RouteRequirements::UUID->value,
    ]
)]
class SkillController extends AbstractControlPanelController
{
    #[Route(
        's',
        name: '_board',
        methods: ['GET']
    )]
    public function list(): ViewResponseDto {
        $employerSkills = [
            [
                'id' => 'skill-id',
                'title' => 'PHP',
                'match' => true,
            ],
            [
                'id' => 'skill-id',
                'title' => 'Machine learning',
                'match' => false,
            ],
        ];

        $mySkills = ['PHP'];

        $form = $this->createForm(MySkillsFormType::class, []);

        return $this->response(
            [
                'skillsForm' => $form,
                'skillsFormActions' => ['add'],
                'mySkills' => $mySkills,
                'employerSkills' => $employerSkills,
                'skillsMatched' => 15,
            ],
            'control-panel/skill/board.html.twig'
        );
    }

    #[Route(
        's',
        name: '_edit',
        methods: ['POST']
    )]
    public function edit(
        Request $request,
    ): ViewResponseDto {
        $mySkills = [];
        $form = $this->createForm(MySkillsFormType::class, $mySkills);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            // TODO attach skills
        }

        return $this->response(
            [],
            'cp_skill_board'
        );
    }
}
