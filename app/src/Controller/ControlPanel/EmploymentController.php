<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\EmploymentBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Employment;
use App\EntityTransformer\EmploymentTransformer;
use App\Form\CommandCenter\Resume\EmploymentRecordFormType;
use App\Repository\EmploymentRepository;
use App\Security\Voter\VoterPermissions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    "/cp/employment",
    name: "cp_employment",
    requirements: [
        'employment' => RouteRequirements::UUID->value,
    ]
)]
class EmploymentController extends AbstractControlPanelController
{
    #[Route(
        path: '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(
        EmploymentBuilder $builder
    ): ViewResponseDto {
        $employment = $builder->base($this->getUser());

        $this->entityManager->persist($employment);
        $this->entityManager->flush();

        return $this->response(
            [
                'employment' => $employment,
            ]
            ,'cp_employment_edit',
        );
    }

    #[Route(
        path: '/{employment}/edit',
        name: '_edit',
        methods: ['GET', 'POST']
    )]
    #[IsGranted(
        VoterPermissions::VIEW->value,
        'employment',
        'Access denied',
        Response::HTTP_UNAUTHORIZED
    )]
    public function edit(
        Request $request,
        Employment $employment,
        EmploymentTransformer $transformer
    ): ViewResponseDto {
        $employmentRecord = $transformer->reverseTransform($employment);
        dump($employmentRecord);
        $employmentForm = $this->createForm(EmploymentRecordFormType::class, $employmentRecord);
        $employmentForm->handleRequest($request);

        if ($employmentForm->isSubmitted() && $employmentForm->isValid()) {
            $dto = $employmentForm->getData();
            dump($dto);

            $actionBtn = $employmentForm->get('actionBtn')->getData();
            dump($actionBtn);

            $entity = $transformer->transform($dto);

            dump($entity);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            if ('view' === $actionBtn) {
                return $this->response(
                    [],
                    'cp_employment_board',
                );
            }
        }

        return $this->response(
            [
                'employmentForm' => $employmentForm,
                'employmentFormActions' => ['save', 'view'],
            ],
            'control-panel/employment/edit.html.twig'
        );
    }

    #[Route(
        's',
        name: '_board',
        methods: ['GET', 'POST']
    )]
    public function list(
        EmploymentRepository $employmentRepository,
        EmploymentTransformer $transformer
    ): ViewResponseDto {
        $entities = $employmentRepository->findBy(['owner' => $this->getUser()], ['createdAt' => 'DESC']);

        $employments = array_map(function (Employment $employment) use ($transformer) {
            return $transformer->reverseTransform($employment);
        }, $entities);

        return $this->response(
            [
                'employments' => $employments,
            ],
            'control-panel/employment/board.html.twig'
        );
    }
}