<?php
declare(strict_types=1);

namespace App\Constants;

use App\Traits\EnumNameToValue;
use App\Traits\EnumToArray;

enum LanguageLevelChoicesEnum: string
{
    use EnumToArray;

    case NATIVE = 'Native speaker';
    case FLUENT = 'Fluent';
    case WORKING_KNOWLEDGE = 'Working knowledge';
    case DOCUMENTATION_READER = 'Documentation reader';
    case HIGHLY_PROFICIENT = 'Highly proficient';
    case A1 = 'A1';
    case A2 = 'A2';
    case B1 = 'B1';
    case B2 = 'B2';
    case C1 = 'C1';
    case C2 = 'C2';
}
