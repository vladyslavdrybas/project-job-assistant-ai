<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Achievement;

use App\DataTransferObject\IDataTransferObject;
use DateTimeImmutable;

class AchievementEmploymentDto implements IDataTransferObject
{
    public function __construct(
        public ?string $jobTitle = null,
        public ?string $projectTitle = null,
        public ?string $employerTitle = null,
        public ?DateTimeImmutable $startDate = null,
        public ?DateTimeImmutable $endDate = null,
        public ?string $employmentId = null
    ) {}
}
