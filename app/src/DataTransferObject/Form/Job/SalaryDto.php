<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Job;

use App\Constants\Job\JobSalaryPeriod;

class SalaryDto
{
    public function __construct(
        public ?string $min = null,
        public ?string $max = null,
        public ?JobSalaryPeriod $period = null,
    ) {}
}
