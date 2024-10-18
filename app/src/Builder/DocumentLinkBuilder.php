<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\DocumentLinkDto;
use App\Entity\CoverLetter;
use App\Entity\Resume;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DocumentLinkBuilder
{
    public function __construct(
        protected readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function fromResume(Resume $resume): DocumentLinkDto
    {
        return new DocumentLinkDto(
            $resume->getTitle(),
            $this->urlGenerator->generate('cp_resume_show', ['resume' => $resume->getId()]),
            $resume->getRawId(),
            $resume->getCreatedAt(),
            $resume->getUpdatedAt()
        );
    }

    public function fromCoverLetter(CoverLetter $coverLetter): DocumentLinkDto
    {
        return new DocumentLinkDto(
            $coverLetter->getTitle(),
            $this->urlGenerator->generate('cp_cover_letter_show', ['coverLetter' => $coverLetter->getId()]),
            $coverLetter->getRawId(),
            $coverLetter->getCreatedAt(),
            $coverLetter->getUpdatedAt()
        );
    }
}
