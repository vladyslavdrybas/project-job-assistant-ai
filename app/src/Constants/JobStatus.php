<?php
declare(strict_types=1);

namespace App\Constants;

// 'saved|applied|archived|rejected|interviewing|offered'
enum JobStatus: string
{
    case SAVED = 'saved';
    case APPLIED = 'applied';
    case INTERVIEWING = 'interviewing';
    case OFFERED = 'offered';
    case REJECTED = 'rejected';
    case ARCHIVED = 'archived';
}
