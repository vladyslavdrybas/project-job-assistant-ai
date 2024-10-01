<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EmploymentHistory;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTimeImmutable;
use DateTimeInterface;

class EmploymentRecordDto implements IDataTransferObject
{
    public function __construct(
        public ?string $jobTitle = null,
        public ?string $projectTitle = null,
        public ?EmployerDto $employer = null,
        public ?ContactPersonDto $contactPerson = null,
        public ?string $description = null,
        public ?DateTimeImmutable $startDate = null,
        public ?DateTimeImmutable $endDate = null,

        /** @var array<string> $formats*/
        public ?array $formats = [],
        /** @var array<string> $skills*/
        public ?array $skills = [],

        public ?string $id = null,
        public ?UserInterface $owner = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}
}
