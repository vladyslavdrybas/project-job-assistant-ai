<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Constants\Job\JobStatus;
use App\Controller\ControlPanel\AbstractControlPanelController;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\Job;
use App\Security\Voter\VoterPermissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    "/api/job",
    name: "api_job",
    requirements: [
        'job' => Requirement::UID_RFC4122,
        'status' => new EnumRequirement(JobStatus::class)
    ]
)]
class UserJobController extends AbstractControlPanelController
{
    #[Route(
        path: '/{job}/to/{status}',
        name: '_status_change',
        methods: ['GET']
    )]
    #[IsGranted(
        VoterPermissions::EDIT->value,
        'job',
        'Access denied',
        Response::HTTP_UNAUTHORIZED
    )]
    public function filter(
        Job $job,
        string $status
    ): ViewResponseDto {
        $fromStatus = $job->getStatus()->value;
        $jobStatus = JobStatus::from($status);
        $job->setStatus($jobStatus);

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $this->response(
            [
                'message' => 'Job status changes.',
                'jobStatus' => [
                    'from' => $fromStatus,
                    'to' => $jobStatus->value,
                ],
            ]
        );
    }
}
