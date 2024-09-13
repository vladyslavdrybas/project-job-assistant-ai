<?php
declare(strict_types=1);

namespace App\Twig;


use Symfony\Component\Finder\Finder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileGetServerPath extends AbstractExtension
{
    public function __construct(
        protected readonly string $projectDir
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fileGetServerPath', array($this, 'get') ),
        ];
    }

    public function get($path): string
    {
        return $this->projectDir . '/public' . dirname($path) . '/' . basename($path);
    }
}
