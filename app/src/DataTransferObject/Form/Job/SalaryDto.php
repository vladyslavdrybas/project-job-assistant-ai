<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Job;

use Symfony\Component\Validator\Constraints as Assert;

class SalaryDto
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(0)]
        public ?int $min = null,
        #[Assert\GreaterThanOrEqual(0)]
        public ?int $max = null,
        public ?string $period = null,
    ) {}
}
