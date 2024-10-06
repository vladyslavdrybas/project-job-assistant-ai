<?php
declare(strict_types=1);

namespace App\Constants\Job;

use App\Traits\EnumToArray;

enum JobSalaryPeriod: string
{
    use EnumToArray;

    case YEAR = 'year';
    case MONTH = 'month';
    case DAY = 'day';
    case HOUR = 'hour';
}
