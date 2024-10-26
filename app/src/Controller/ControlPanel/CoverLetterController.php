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
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use League\CommonMark\CommonMarkConverter;

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
                'dto' => $dto,
                'avatarPath' => 'avatar/me.jpeg',
                'navActions' => [
                    'edit' => [
                        'type' => 'link',
                        'title' => 'Edit',
                        'link' => $this->generateUrl('cp_cover_letter_edit', ['coverLetter' => $dto->id]),
                    ],
                    'pdf' => [
                        'type' => 'link',
                        'title' => 'PDF',
                        'link' => $this->generateUrl('cp_cover_letter_pdf', ['coverLetter' => $dto->id]),
                    ],
                ],
            ],
            'control-panel/cover-letter/show.html.twig'
        );
    }

    #[Route(
        '/{coverLetter}/pdf',
        name: '_pdf',
        methods: ['GET']
    )]
    public function pdf(
        CoverLetter $coverLetter,
        CoverLetterTransformer $transformer,
        Pdf $pdf,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ): BinaryFileResponse {
        $dto = $transformer->reverseTransform($coverLetter);

        $data = $serializer->serialize($dto, 'json', [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function ($obj){return $obj->getRawId();}]);
        $filepath = $this->projectDir . '/public/pdf/' . md5($dto->owner->getRawId() . $dto->id . $data) . '.pdf';

        if (!file_exists($filepath) || true) {
            $pdf->setOptions([
//            'grayscale' => true,
                'orientation' => 'portrait',
                'enable-local-file-access' => true,
                'enable-internal-links' => true,
                'enable-external-links' => true,
                'viewport-size' => '1280x1024',
//            'viewport-size' => '1480x4024',

                "margin-bottom" => 8,
                "margin-left" => 3,
                "margin-right" => 3,
                "margin-top" => 8,
                "page-height" => null,
                "page-size" => 'A4',
                "page-width" => null,
//            "viewport-size" => 1.0,
                "no-header-line" => true,
                "no-footer-line" => true,
                "zoom" => 1.1,
                "lowquality" => true,
            ]);

            $config = [
                'renderer' => [
                    'block_separator' => "\n",
                    'inner_separator' => "\n",
                    'soft_break'      => "\n",
                ],
                'commonmark' => [
                    'enable_em' => true,
                    'enable_strong' => true,
                    'use_asterisk' => true,
                    'use_underscore' => true,
                    'unordered_list_markers' => ['-', '*', '+'],
                ],
                'html_input' => 'allow',
                'allow_unsafe_links' => false,
                'max_nesting_level' => PHP_INT_MAX,
                'slug_normalizer' => [
                    'max_length' => 255,
                ],
            ];

            $converter = new CommonMarkConverter($config);

            $html = $this->renderView(
                'control-panel/cover-letter/template/print.html.twig',
                [
                    'dto' => $dto,
                    'avatarPath' => 'avatar/me.jpeg',
                ]
            );

            $pdf->generateFromHtml(
                $html,
                $filepath,
                [],
                true
            );
        }

        return new BinaryFileResponse($filepath);
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
            } elseif ('pdf' === $actionBtn) {
                return $this->response(
                    [
                        'coverLetter' => $coverLetter,
                    ],
                    'cp_cover_letter_pdf',
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
