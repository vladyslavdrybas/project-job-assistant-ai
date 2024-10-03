<?php
declare(strict_types=1);

namespace App\Twig;

use App\Constants\Job\JobFormats;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnumValuesFromCasesFunction extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('enum_values', array($this, 'process')),
        ];
    }

    public function process(array $values, string $type): array
    {
        $cases = match ($type) {
            'job_format' => JobFormats::array(),
            default => throw new Exception('Enum conversion. Unexpected value type'),
        };

        $intersect = [];
        foreach ($values as $value) {
            $intersect[] = ucfirst($cases[$value]);
        }

        return $intersect;
    }
}
