<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\ResumeBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\Form\ResumeDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Resume;
use App\EntityTransformer\ResumeTransformer;
use App\Form\CommandCenter\Resume\ResumeFormType;
use App\Services\Skills\Writer\ResumeSkillsWriter;
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
        ResumeTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($resume);
        dump($dto);

        return $this->response(
            [
                'resume' => $dto,
                'navActions' => [
                    'edit' => [
                        'type' => 'link',
                        'title' => 'Edit',
                        'link' => $this->generateUrl('cp_resume_edit', ['resume' => $dto->id]),
                    ],
                    'pdf' => [
                        'type' => 'link',
                        'title' => 'PDF',
                        'link' => $this->generateUrl('cp_resume_edit', ['resume' => $dto->id]),
                    ],
                ],
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
        ResumeSkillsWriter $resumeSkillsWriter,
        Request $request
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($resume);
        dump($dto);

        $editForm = $this->createForm(ResumeFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var ResumeDto $dto */
            $dto = $editForm->getData();
            $this->saveFromDto($dto, $transformer, $resumeSkillsWriter);

            $actionBtn = $editForm->get('actionBtn')->getData();
            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'resume' => $resume,
                    ],
                    'cp_resume_show',
                );
            }
        }

        return $this->response(
            [
                'editForm' => $editForm,
                'editFormActions' => ['view','save','pdf'],
            ],
            'control-panel/resume/edit.html.twig'
        );
    }

    protected function saveFromDto(
        ResumeDto $dto,
        ResumeTransformer $transformer,
        ResumeSkillsWriter $resumeSkillsWriter
    ): void {
        $entity = $transformer->transform($dto);
        $resumeSkillsWriter->write($entity, $dto->skills);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
