<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\DataTransferObject\ViewResponseDto;
use App\Repository\CoverLetterRepository;
use App\Repository\ResumeRepository;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/document",
    name: "cp_document"
)]
class DocumentController extends AbstractControlPanelController
{
    #[Route(
        's',
        name: '_board',
        methods: ['GET']
    )]
    public function list(
        ResumeRepository $resumeRepository,
        CoverLetterRepository $coverLetterRepository,
    ): ViewResponseDto {
        $resumes = $resumeRepository->findBy(['owner' => $this->getUser()]);
        $coverLetters = $coverLetterRepository->findBy(['owner' => $this->getUser()]);

        return $this->response(
            [
                'resumes' => $resumes,
                'coverLetters' => $coverLetters,
            ],
            'control-panel/document/list-kanban.html.twig'
        );
    }

    #[Route(
        path: 's/filter/{status}',
        name: '_filter',
        methods: ['GET']
    )]
    public function filter(
        string $status
    ): ViewResponseDto {
        return $this->response(
            [
                'status' => $status,
            ]
            ,'control-panel/document/filter.html.twig',
        );
    }
}
