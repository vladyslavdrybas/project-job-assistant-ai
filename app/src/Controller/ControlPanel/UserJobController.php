<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\JobBuilder;
use App\Constants\JobStatus;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Job;
use App\EntityTransformer\JobTransformer;
use App\Form\CommandCenter\Job\JobFormType;
use App\Security\Voter\VoterPermissions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    "/cp/job",
    name: "cp_job",
    requirements: [
        'job' => Requirement::UID_RFC4122,
        'filterType' => new EnumRequirement(JobStatus::class)
    ]
)]
class UserJobController extends AbstractControlPanelController
{
    #[Route(
        path: '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(
        JobBuilder $builder
    ): ViewResponseDto {
        $job = $builder->base($this->getUser());

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $this->response(
            [
                'job' => $job,
            ]
            ,'cp_job_edit',
        );
    }

    #[Route(
        path: '/{job}',
        name: '_show',
        methods: ['GET']
    )]
    #[IsGranted(
        VoterPermissions::VIEW->value,
        'job',
        'Access denied',
        Response::HTTP_UNAUTHORIZED
    )]
    public function show(
        Job $job
    ): ViewResponseDto {
        return $this->response(
            [
                'job' => $job,
            ]
            ,'control-panel/job/show.html.twig',
        );
    }

    #[Route(
        path: '/{job}/tailor/resume/{resume}',
        name: '_tailor_resume',
        methods: ['GET']
    )]
    #[IsGranted(
        VoterPermissions::VIEW->value,
        'job',
        'Access denied',
        Response::HTTP_UNAUTHORIZED
    )]
    public function tailorResume(
        Job $job
    ): ViewResponseDto {
        return $this->response(
            []
            ,'control-panel/job/show.html.twig',
        );
    }

    #[Route(
        path: '/{job}/edit',
        name: '_edit',
        methods: ['GET']
    )]
    #[IsGranted(
        VoterPermissions::VIEW->value,
        'job',
        'Access denied',
        Response::HTTP_UNAUTHORIZED
    )]
    public function edit(
        Request $request,
        Job $job,
        JobTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($job);
        dump($dto);

        $editForm = $this->createForm(JobFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            dump('form submitted');
            dump($editForm->getData());
        }

        return $this->response(
            [
                'editForm' => $editForm,
                'editFormActions' => ['save'],
            ]
            ,'control-panel/job/edit.html.twig',
        );
    }

    #[Route(
        path: 's',
        name: '_list',
        methods: ['GET']
    )]
    public function list(): ViewResponseDto {
        $jobs = [];

        return $this->response(
            [
                'jobs' => $jobs,
            ]
            ,'control-panel/job/list.html.twig',
        );
    }

    #[Route(
        path: 's/filter/{filterType}',
        name: '_filter',
        methods: ['GET']
    )]
    public function filter(
        string $filterType
    ): ViewResponseDto {
        return $this->response(
            [
                'filterType' => $filterType,
            ]
            ,'control-panel/job/filter.html.twig',
        );
    }
}
