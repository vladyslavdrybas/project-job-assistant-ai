<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\Form\Achievement\AchievementDto;
use App\DataTransferObject\Form\Achievement\AchievementEmploymentDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Achievement;
use App\EntityTransformer\AchievementTransformer;
use App\Exceptions\AccessDenied;
use App\Form\CommandCenter\Achievement\AchievementFormType;
use App\Repository\AchievementRepository;
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
        AchievementRepository $achievementRepository,
        AchievementTransformer $achievementTransformer
    ): ViewResponseDto {
        $achievements = $achievementRepository
            ->findBy(
                [
                    'owner' => $this->getUser(),
                ],
                [
                    'doneAt' => 'DESC',
                ]
            );

        $achievements = array_map(
            function (Achievement $achievement)
            use ($achievementTransformer): AchievementDto {
                return $achievementTransformer->reverseTransform($achievement);
            },
            $achievements
        );

        return $this->response(
            [
                'achievements' => $achievements,
            ],
            'control-panel/achievement/board.html.twig'
        );
    }

    #[Route(
        '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(): ViewResponseDto {
        $entity = new Achievement();
        $entity->setOwner($this->getUser());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->response(
            [
                'achievement' => $entity->getRawId(),
            ],
            'cp_achievement_edit'
        );
    }

    #[Route(
        '/{achievement}/edit',
        name: '_edit',
        methods: ['GET','POST']
    )]
    public function edit(
        Request $request,
        Achievement $achievement,
        AchievementTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($achievement);
        if (null === $dto->owner) {
            throw new AccessDenied();
        }

        $form = $this->createForm(AchievementFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AchievementDto $data */
            $data = $form->getData();
            $actionBtn = $form->get('actionBtn')->getData();

            $employment = $form->get('employment')->getData();
            if (null !== $employment) {
                $employment = new AchievementEmploymentDto(
                    $employment->getJobTitle(),
                    $employment->getProjectTitle(),
                    $employment->getEmployer()?->title ?? null,
                    $employment->getStartDate(),
                    $employment->getEndDate(),
                    $employment->getRawId(),
                );
            }
            $data->employment = $employment;

            $entity = $transformer->transform($dto);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'achievement' => $entity,
                    ],
                    'cp_achievement_show'
                );
            } else if ('saveandnew' === $actionBtn) {
                return $this->response(
                    [],
                    'cp_achievement_add'
                );
            }
        }

        return $this->response(
            [
                'form' => $form,
                'formActions' => ['save', 'view', 'saveandnew']
            ],
            'control-panel/achievement/edit.html.twig'
        );
    }



    #[Route(
        '/{achievement}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(
        Achievement $achievement,
        AchievementTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($achievement);

        return $this->response(
            [
                'achievement' => $dto,
                'navActions' => [
                    'edit' => [
                        'type' => 'link',
                        'title' => 'Edit',
                        'link' => $this->generateUrl('cp_achievement_edit', ['achievement' => $achievement]),
                    ],
                ],
            ],
            'control-panel/achievement/show.html.twig'
        );

    }
}
