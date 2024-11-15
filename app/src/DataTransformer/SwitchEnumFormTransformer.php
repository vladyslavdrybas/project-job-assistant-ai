<?php
declare(strict_types=1);

namespace App\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class SwitchEnumFormTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): array
    {
        if (!is_string($value)) {
            return [];
        }

        $data = [];
        $data[strtolower($value)] = true;

        return $data;
    }

    public function reverseTransform(mixed $value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        foreach ($value as $format => $isEnable) {
            if ($isEnable) {
                return strtoupper($format);
            }
        }

        return null;
    }
}
