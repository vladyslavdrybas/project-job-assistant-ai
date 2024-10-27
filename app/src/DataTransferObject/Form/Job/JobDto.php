<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Job;

use App\Constants\Job\JobStatus;
use App\DataTransferObject\DocumentLinkDto;
use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\Contact\LocationDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTimeInterface;

class JobDto implements IDataTransferObject
{
    public function __construct(
        public ?UserInterface $owner = null,
        public ?string $title = null,
        public ?string $content = null,
        public ?string $estimateContent = null,
        public ?string $aboutPage = null,
        public JobStatus $status = JobStatus::BACKLOG,
        public ?EmployerDto $employer = null,
        public ?LocationDto $location = null,
        public ?ContactPersonDto $contactPerson = null,
        public ?SalaryDto $salary = null,

        /** @var array<string> $formats*/
        public ?array $formats = [],
        /** @var array<string> $skills*/
        public ?array $skills = [],

        public ?DocumentLinkDto $resume = null,
        public ?DocumentLinkDto $coverLetter = null,

        public bool $isFavorite = false,
        public bool $isUserAdded = false,
        public ?string $id = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}
}
