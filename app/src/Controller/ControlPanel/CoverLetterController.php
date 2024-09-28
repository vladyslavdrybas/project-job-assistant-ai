<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\CoverLetterBuilder;
use App\Builder\ResumeBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\CoverLetter;
use App\Entity\Resume;
use App\EntityTransformer\CoverLetterTransformer;
use App\EntityTransformer\ResumeTransformer;
use App\Form\CommandCenter\CoverLetter\SimpleCoverLetterFormType;
use App\Form\CommandCenter\Resume\ResumeFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/cl",
    name: "cp_cover_letter",
    requirements: [
        'coverLetter' => RouteRequirements::UUID->value,
    ]
)]
class CoverLetterController extends AbstractControlPanelController
{
    // create a new empty cover letter and redirect to edit
    #[Route(
        '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(
        CoverLetterBuilder $builder
    ): ViewResponseDto {
        $entity = $builder->base($this->getUser());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->response(
            [
                'coverLetter' => $entity->getRawId(),
            ],
            'cp_cover_letter_edit'
        );
    }

    #[Route(
        '/{coverLetter}/edit',
        name: '_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(
        CoverLetter $coverLetter,
        CoverLetterTransformer $transformer,
        Request $request
    ): ViewResponseDto {
        dump($coverLetter);

        $dto = $transformer->reverseTransform($coverLetter);
        dump($dto);

        $editForm = $this->createForm(SimpleCoverLetterFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // TODO handle form changes
            dump($editForm->getData());
            if (filter_var($editForm->get('isNeedAiHelp')->getData(), FILTER_VALIDATE_BOOLEAN)) {
                // save changes and redirect to cp_cover_letter_edit_ai
                return $this->response(
                    [
                        'coverLetter' => $coverLetter,
                    ],
                    'cp_cover_letter_edit_ai'
                );
            }
        }

        return $this->response(
            [
                'editForm' => $editForm,
            ],
            'control-panel/cover-letter/edit.html.twig'
        );
    }

    #[Route(
        '/{coverLetter}/edit/ai',
        name: '_edit_ai',
        methods: ['GET', 'POST']
    )]
    public function editAi(
        CoverLetter $coverLetter,
        CoverLetterTransformer $transformer,
        Request $request
    ): ViewResponseDto {
        dump($coverLetter);

        $dto = $transformer->reverseTransform($coverLetter);
        dump($dto);

        $editForm = $this->createForm(SimpleCoverLetterFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // TODO handle form changes
            dump($editForm->getData());
            if (filter_var($editForm->get('isNeedAiHelp')->getData(), FILTER_VALIDATE_BOOLEAN)) {
                // save changes and redirect to cp_cover_letter_edit_ai
                return $this->response(
                    [
                        'editForm' => $editForm,
                    ],
                    'cp_cover_letter_edit_ai'
                );
            }
        }

        return $this->response(
            [
                'editForm' => $editForm,
            ],
            'control-panel/cover-letter/edit-ai.html.twig'
        );
    }
}
