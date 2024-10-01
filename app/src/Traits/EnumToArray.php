<?php
declare(strict_types=1);

namespace App\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::names(), self::values());
    }

    public static function nameToValue(string $name): mixed
    {
        return array_flip(self::array())[$name] ?? null;
    }
}
