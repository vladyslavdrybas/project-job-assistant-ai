<?php
declare(strict_types=1);

namespace App\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class JobFormatsTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $data = [];

        foreach ($value as $format) {
            $data[strtolower($format)] = true;
        }

        return $data;
    }

    public function reverseTransform(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $data = [];
        foreach ($value as $format => $isEnable) {
            if ($isEnable) {
                $data[] = strtoupper($format);
            }
        }

        return $data;
    }
}
