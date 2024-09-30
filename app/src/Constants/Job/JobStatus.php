<?php
declare(strict_types=1);

namespace App\Constants\Job;

use App\Traits\EnumToArray;

enum JobStatus: string
{
    use EnumToArray;

    case BACKLOG = 'backlog';
    case APPLIED = 'applied';
    case INTERVIEWING = 'interviewing';
    case OFFERED = 'offered';
    case ARCHIVED = 'archived';
}
