<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Form\CommandCenter\Skill\MySkillsFormType;
use App\Repository\EmploymentRepository;
use App\Repository\JobRepository;
use App\Services\Skills\Writer\UserSkillsWriter;
use App\Utility\MatchUserSkills;
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
    public function list(
        JobRepository $jobRepository
    ): ViewResponseDto {
        $myJobs = $jobRepository->findBy(['owner' => $this->getUser()]);

        $jobSkills = [];
        foreach ($myJobs as $job) {
            $jobSkills = array_merge($jobSkills, $job->getSkills() ?? []);
        }
        $jobSkills = array_unique($jobSkills);

        $mySkills = $this->getUser()->getFilterSkills()->toArray();
        [
            'mySkills' => $mySkills,
            'otherSkills' => $jobSkills,
            'skillsMatched' => $skillsMatched,
        ] = (new MatchUserSkills())($mySkills, $jobSkills);

        $form = $this->createForm(MySkillsFormType::class, []);

        return $this->response(
            [
                'skillsForm' => $form,
                'skillsFormActions' => ['add'],
                'mySkills' => $mySkills,
                'jobSkills' => $jobSkills,
                'skillsMatched' => $skillsMatched,
            ],
            'control-panel/skill/board.html.twig'
        );
    }

    #[Route(
        's',
        name: '_add',
        methods: ['POST']
    )]
    public function edit(
        Request $request,
        UserSkillsWriter $userSkillsWriter
    ): ViewResponseDto {
        $mySkills = [];
        $form = $this->createForm(MySkillsFormType::class, $mySkills);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skillsToAdd = $form->get('skills')->getData();
            $userSkillsWriter->write($this->getUser(), $skillsToAdd);
        }

        return $this->response(
            [],
            'cp_skill_board'
        );
    }
}
