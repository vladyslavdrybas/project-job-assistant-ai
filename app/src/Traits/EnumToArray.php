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
        return self::array()[$name] ?? null;
    }

    public static function hasKey(string $name): bool
    {
        return self::array()[$name] !== null;
    }

    public static function fromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
