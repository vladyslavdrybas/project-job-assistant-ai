<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Job;

use App\Constants\JobStatus;
use App\DataTransferObject\Form\Contact\ContactPersonDto;
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
        public ?string $aboutPage = null,
        public JobStatus $status = JobStatus::NEW,
        public ?EmployerDto $employer = null,
        public ?ContactPersonDto $contactPerson = null,

        /** @var array<string> $skills*/
        public array $skills = [],

        public bool $isUserAdded = false,
        public ?string $id = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}
}
