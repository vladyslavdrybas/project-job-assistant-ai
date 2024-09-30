<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\ResumeBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Resume;
use App\EntityTransformer\ResumeTransformer;
use App\Form\CommandCenter\Resume\ResumeFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/resume",
    name: "cp_resume",
    requirements: [
        'resume' => RouteRequirements::UUID->value,
    ]
)]
class ResumeController extends AbstractControlPanelController
{
    // create a new empty resume and redirect to edit
    #[Route(
        '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(
        ResumeBuilder $resumeBuilder
    ): ViewResponseDto {
        $resume = $resumeBuilder->base($this->getUser());

        $this->entityManager->persist($resume);
        $this->entityManager->flush();

        return $this->response(
            [
                'resume' => $resume->getRawId(),
            ],
            'cp_resume_edit'
        );
    }

    #[Route(
        '/{resume}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(
        Resume $resume,
    ): ViewResponseDto {
        dump($resume);

        return $this->response(
            [
                'resume' => $resume,
            ],
            'control-panel/resume/show.html.twig'
        );
    }

    #[Route(
        '/{resume}/edit',
        name: '_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(
        Resume $resume,
        ResumeTransformer $transformer,
        Request $request
    ): ViewResponseDto {
        dump($resume);

        $dto = $transformer->reverseTransform($resume);
        dump($dto);

        $editForm = $this->createForm(ResumeFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // TODO handle form changes
            dump($editForm->getData());
        }

        return $this->response(
            [
                'editForm' => $editForm,
                'editFormActions' => ['preview','save','pdf'],
            ],
            'control-panel/resume/edit.html.twig'
        );
    }
}
