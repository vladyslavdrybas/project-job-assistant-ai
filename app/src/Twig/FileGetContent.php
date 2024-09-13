<?php
declare(strict_types=1);

namespace App\Twig;


use Symfony\Component\Finder\Finder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileGetContent extends AbstractExtension
{
    public function __construct(
        protected readonly string $projectDir
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fileGetContent', array($this, 'get') ),
        ];
    }

    public function get($path): string
    {
        $finder = new Finder();

        $finder->files()->in($this->projectDir . '/public' . dirname($path))->name(basename($path));

        $content = '';
        foreach ($finder as $file) {

            if (in_array($file->getExtension(), ['jpg','jpeg','png','gif'])) {
                $mime = match($file->getExtension()) {
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    default => 'application/octet-stream',
                };
                $content = 'data:' . $mime . ';base64,' . base64_encode($file->getContents());
            } else {
                $content = $file->getContents();
            }
        }

        return $content;
    }
}
