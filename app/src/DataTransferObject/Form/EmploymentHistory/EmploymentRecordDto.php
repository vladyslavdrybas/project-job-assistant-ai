<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EmploymentHistory;

use App\DataTransferObject\IDataTransferObject;
use DateTime;

class EmploymentRecordDto implements IDataTransferObject
{
    public function __construct(
        public ?string $jobTitle = null,
        public ?EmployerDto $employer = null,
        public ?string $description = null,
        public ?DateTime $startDate = null,
        public ?DateTime $endDate = null
    ) {}
}
