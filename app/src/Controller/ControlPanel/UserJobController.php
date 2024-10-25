<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\DocumentLinkBuilder;
use App\Builder\JobBuilder;
use App\Constants\Job\JobStatus;
use App\DataTransferObject\Form\Job\JobDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Job;
use App\Entity\Resume;
use App\EntityTransformer\JobTransformer;
use App\Form\CommandCenter\Job\JobFormType;
use App\Repository\CoverLetterRepository;
use App\Repository\JobRepository;
use App\Repository\ResumeRepository;
use App\Security\Voter\VoterPermissions;
use App\Services\Skills\Writer\JobSkillsWriter;
use App\Utility\MatchUserSkills;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    "/cp/job",
    name: "cp_job",
    requirements: [
        'job' => Requirement::UID_RFC4122,
        'status' => new EnumRequirement(JobStatus::class)
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
        Job $job,
        JobTransformer $transformer
    ): ViewResponseDto {

        $dto = $transformer->reverseTransform($job);

        $mySkills = $this->getUser()->getFilterSkills()->toArray();
        [
            'otherSkills' => $jobSkills,
            'skillsMatched' => $skillsMatched,
        ] = (new MatchUserSkills())($mySkills, $dto->skills ?? []);

        return $this->response(
            [
                'job' => $dto,
                'jobSkills' => $jobSkills,
                'jobSkillsMatched' => $skillsMatched,
                'jobBenefits' => [],
                'navActions' => [
                    'edit' => [
                        'type' => 'link',
                        'title' => 'Edit',
                        'link' => $this->generateUrl('cp_job_edit', ['job' => $dto->id]),
                    ],
                ],
            ]
            ,'control-panel/job/show.html.twig',
        );
    }

    #[Route(
        path: '/{job}/edit',
        name: '_edit',
        methods: ['GET', 'POST']
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
        JobTransformer $transformer,
        JobSkillsWriter $jobSkillsWriter,
        ResumeRepository $resumeRepository,
        CoverLetterRepository $coverLetterRepository,
        DocumentLinkBuilder $documentLinkBuilder
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($job);
        $editForm = $this->createForm(JobFormType::class, $dto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var JobDto $dto */
            $dto = $editForm->getData();
            $actionBtn = $editForm->get('actionBtn')->getData();
            $resumeId = $editForm->get('resumeId')->getData();
            $coverLetterId = $editForm->get('coverLetterId')->getData();

            $entity = $transformer->transform($dto);

            if (null !== $resumeId) {
                $resume = $resumeRepository->find($resumeId);
                if (null !== $resume) {
                    $entity->setResume($resume);
                    $resume->addJob($entity);
                    $this->entityManager->persist($resume);
//                    $dto->resume = $documentLinkBuilder->fromResume($resume);
                }
            }

            if (null !== $coverLetterId) {
                $coverLetter = $coverLetterRepository->find($coverLetterId);
                if (null !== $coverLetter) {
                    $entity->setCoverLetter($coverLetter);
                    $coverLetter->addJob($entity);
                    $this->entityManager->persist($coverLetter);
//                    $dto->coverLetter = $documentLinkBuilder->fromCoverLetter($coverLetter);
                }
            }

            $dto->isUserAdded = true;


            $jobSkillsWriter->write($entity, $entity->getSkills());

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'job' => $job,
                    ],
                    'cp_job_show'
                );
            }
        }

        return $this->response(
            [
                'job' => $dto,
                'editForm' => $editForm,
                'editFormActions' => ['save', 'view'],
            ]
            ,'control-panel/job/edit.html.twig',
        );
    }

    #[Route(
        path: 's',
        name: '_board',
        methods: ['GET']
    )]
    public function board(
        JobRepository $jobRepository,
        JobTransformer $transformer
    ): ViewResponseDto {
        $entities = $jobRepository->findListForJobBoard($this->getUser());

        $dtos = array_map(function(Job $job) use ($transformer) {
            return $transformer->reverseTransform($job);
        }, $entities);

        $jobs = JobStatus::values();
        $jobs = array_flip($jobs);
        $jobs = array_map(fn() => [], $jobs);

        $statuses = JobStatus::values();
        $statuses = array_filter(
            $statuses,
            fn(string $status) => JobStatus::ARCHIVED->value !== $status
        );

        foreach($dtos as $jobDto) {
            $jobs[$jobDto->status->value][] = $jobDto;
        }

        return $this->response(
            [
                'jobs' => $jobs,
                'jobStatuses' => $statuses,
                'colWidth' => (int) ceil(12/count($statuses)),
            ]
            ,'control-panel/job/list-kanban.html.twig',
        );
    }

    #[Route(
        path: 's/filter/{status}',
        name: '_filter',
        methods: ['GET']
    )]
    public function filter(
        JobRepository $jobRepository,
        JobTransformer $transformer,
        string $status
    ): ViewResponseDto {
        $status = JobStatus::from($status);
        $entities = $jobRepository->findListForJobBoard($this->getUser(), $status);

        $dtos = array_map(function(Job $job) use ($transformer) {
            return $transformer->reverseTransform($job);
        }, $entities);

        $jobs = [
            JobStatus::BACKLOG->value => [],
            $status->value => [],
        ];

        $statuses = [
            JobStatus::BACKLOG->value,
            $status->value,
        ];

        foreach($dtos as $jobDto) {
            $jobs[$jobDto->status->value][] = $jobDto;
        }

        return $this->response(
            [
                'status' => $status,
                'jobs' => $jobs,
                'jobStatuses' => $statuses,
                'colWidth' => (int) ceil(12/count($statuses)),
            ]
            ,'control-panel/job/list-kanban.html.twig',
        );
    }
}
