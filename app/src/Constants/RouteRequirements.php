<?php
declare(strict_types=1);

namespace App\Constants;

enum RouteRequirements: string
{
    case UUID = '^[0-9a-f]{8}-[0-9a-f]{4}-[13-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$';
    case UNIQUE_ID = '^[a-z0-9]{13,14}\.[a-z0-9]{6,11}$';
    case USER_ALIAS = '^[a-z0-9\.]{3,100}$';
    case USER_SHA256 = '^[a-z0-9]{64}$';
}
