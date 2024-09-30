<?php
declare(strict_types=1);

namespace App\Constants\Job;

use App\Traits\EnumToArray;

enum JobFormats: string
{
    use EnumToArray;

    case REMOTE = 'remote';
    case HYBRID = 'hybrid';
    case ON_SITE = 'on-site';
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';
    case INTERNSHIP = 'internship';
    case CONTRACT = 'contract';
}
