<?php
declare(strict_types=1);

namespace App\Twig;


use DateTime;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateDiffToStringFunction extends AbstractExtension
{
    public function __construct(
        protected readonly string $projectDir
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('date_diff', array($this, 'process') ),
        ];
    }

    public function process(?DateTimeInterface $from = null, ?DateTimeInterface $to = null): string
    {
        if ($to === null && $from === null) {
            return 'now';
        }

        if ($to === null) {
            $to = new DateTime('now');
        }

        $diff = $from->diff($to);
        $weeks = (int) ceil($diff->m/30);

        return match(true) {
            $diff->y > 1 => $diff->y . ' years ago',
            $diff->y === 1 => 'a year ago',
            $weeks > 1 => $weeks . ' weeks ago',
            $weeks === 1 => 'a week ago',
            $diff->d > 1 => $diff->y . ' days ago',
            $diff->d === 1 => 'a day ago',
            $diff->h > 1 => $diff->h . ' hours ago',
            $diff->h === 1 => 'an hour ago',
            $diff->i > 1 => $diff->i . ' minutes ago',
            $diff->i === 1 => 'a minute ago',
            default => 'recently'
        };
    }
}
