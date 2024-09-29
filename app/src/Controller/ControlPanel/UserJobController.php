<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\JobStatus;
use App\DataTransferObject\ViewResponseDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Uid\Uuid;

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
        path: '/{job}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(): ViewResponseDto {
        return $this->response(
            []
            ,'control-panel/job/show.html.twig',
        );
    }

    #[Route(
        path: '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(): ViewResponseDto {
        return $this->response(
            [
                'job' => Uuid::v7(),
            ]
            ,'cp_job_edit',
        );
    }

    #[Route(
        path: '/{job}/edit',
        name: '_edit',
        methods: ['GET']
    )]
    public function edit(): ViewResponseDto {
        return $this->response(
            []
            ,'control-panel/job/edit.html.twig',
        );
    }

    #[Route(
        path: 's',
        name: '_list',
        methods: ['GET']
    )]
    public function list(): ViewResponseDto {
        return $this->response(
            []
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
