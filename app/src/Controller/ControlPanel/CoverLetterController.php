<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\CoverLetterBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\Form\CoverLetterAiDto;
use App\DataTransferObject\Form\CoverLetterDto;
use App\DataTransferObject\Form\ResumeDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\CoverLetter;
use App\EntityTransformer\CoverLetterTransformer;
use App\Form\CommandCenter\CoverLetter\AiCoverLetterFormType;
use App\Form\CommandCenter\CoverLetter\SimpleCoverLetterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/cover-letter",
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
        '/{coverLetter}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(
        CoverLetter $coverLetter,
        CoverLetterTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($coverLetter);
        dump($coverLetter);

        return $this->response(
            [
                'coverLetter' => $dto,
                'navActions' => [
                    'edit' => [
                        'type' => 'link',
                        'title' => 'Edit',
                        'link' => $this->generateUrl('cp_cover_letter_edit', ['coverLetter' => $dto->id]),
                    ],
                    'pdf' => [
                        'type' => 'link',
                        'title' => 'PDF',
                        'link' => $this->generateUrl('cp_cover_letter_edit', ['coverLetter' => $dto->id]),
                    ],
                ],
            ],
            'control-panel/cover-letter/show.html.twig'
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
        $dto = $transformer->reverseTransform($coverLetter);
        if (null === $dto->owner) {
            $dto->owner = $this->getUser();
        }
        dump($dto);

        $editForm = $this->createForm(SimpleCoverLetterFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var CoverLetterDto $dto */
            $dto = $editForm->getData();
            dump($dto);

            $actionBtn = $editForm->get('actionBtn')->getData();
            dump($actionBtn);

            $entity = $transformer->transform($dto);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'coverLetter' => $coverLetter,
                    ],
                    'cp_cover_letter_show',
                );
            }
        }

        return $this->response(
            [
                'coverLetter' => $dto,
                'editForm' => $editForm,
                'editFormActions' => ['save', 'view', 'pdf', 'ai'],
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

        $coverLetterDto = $transformer->reverseTransform($coverLetter);

        $dto = new CoverLetterAiDto(
            $coverLetterDto->owner,
            $coverLetterDto,
            new ResumeDto()
        );

        dump($dto);

        $editForm = $this->createForm(AiCoverLetterFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // TODO handle form changes
            dump($editForm->getData());
            $actionBtn = $editForm->get('actionBtn')->getData();
            dump($actionBtn);

            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'coverLetter' => $coverLetter,
                    ],
                    'cp_cover_letter_show',
                );
            }
        }

        return $this->response(
            [
                'editForm' => $editForm,
                'editFormActions' => ['save', 'view', 'pdf'],
            ],
            'control-panel/cover-letter/edit-ai.html.twig'
        );
    }
}
