<?php
declare(strict_types=1);

namespace App\Constants\InterviewQuestion;

use App\Traits\EnumToArray;

enum InterviewQuestionCategory: string
{
    use EnumToArray;

    case COMMON = 'common';
    case ENGINEERING = 'engineering';
    case DATABASE = 'database';
    case CODING = 'coding';
}
