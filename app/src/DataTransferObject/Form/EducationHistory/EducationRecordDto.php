<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EducationHistory;

use App\DataTransferObject\IDataTransferObject;
use DateTime;

class EducationRecordDto implements IDataTransferObject
{
    public function __construct(
        public ?string $degree = null,
        public ?SchoolDto $school = null,
        public ?string $description = null,
        public ?DateTime $startDate = null,
        public ?DateTime $endDate = null
    ) {}
}
