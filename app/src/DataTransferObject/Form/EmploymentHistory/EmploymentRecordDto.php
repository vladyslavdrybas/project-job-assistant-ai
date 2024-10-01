<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EmploymentHistory;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTime;
use DateTimeInterface;

class EmploymentRecordDto implements IDataTransferObject
{
    public function __construct(
        public ?string $jobTitle = null,
        public ?string $projectTitle = null,
        public ?EmployerDto $employer = null,
        public ?string $description = null,
        public ?DateTime $startDate = null,
        public ?DateTime $endDate = null,

        /** @var array<string> $formats*/
        public array $formats = [],
        /** @var array<string> $skills*/
        public array $skills = [],

        public ?string $id = null,
        public ?UserInterface $owner = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}
}
