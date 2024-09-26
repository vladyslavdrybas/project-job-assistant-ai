<?php
declare(strict_types=1);

namespace App\Twig;


use App\Constants\LanguageLevelChoicesEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class LanguageLevelFilter extends AbstractExtension
{
    public function __construct(
        protected readonly string $projectDir
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('languageLabel', array($this, 'filter') ),
        ];
    }

    public function filter($key): ?string
    {
        return LanguageLevelChoicesEnum::nameToValue($key);
    }
}
