<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Traits\EnumToArray;

enum VoterPermissions: string
{
    use EnumToArray;

    case READ = 'read';
    case UPDATE = 'update';
    case VIEW = 'view';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case MANAGE = 'manage';
    case OWNER = 'owner';
}
