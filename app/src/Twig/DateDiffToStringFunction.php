<?php
declare(strict_types=1);

namespace App\Twig;

use DateTime;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateDiffToStringFunction extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('date_diff', array($this, 'process') ),
        ];
    }

    public function process(?DateTimeInterface $from = null, ?DateTimeInterface $to = null, bool $isPast = true): string
    {
        if ($to === $from) {
            return 'now';
        }

        if ($to === null) {
            $to = new DateTime('now');
        }

        if ($from > $to) {
            return '';
        }

        $diff = $from->diff($to);
        $weeks = (int) floor($diff->days/7);

        return (match(true) {
            $diff->y > 1 => sprintf('%s years, %s months', $diff->y, $diff->m),
            $diff->y === 1 => sprintf('1 year, %s months', $diff->m),
            $diff->m > 1 => $diff->m . ' months',
            $diff->m === 1 => 'a month',
            $weeks > 1 => $weeks . ' weeks',
            $weeks === 1 => 'a week',
            $diff->d > 1 => $diff->d . ' days',
            $diff->d === 1 => 'a day',
            $diff->h > 1 => $diff->h . ' hours',
            $diff->h === 1 => 'an hour',
            $diff->i > 1 => $diff->i . ' minutes',
            $diff->i === 1 => 'a minute',
            default => 'recently'
        }) . ($isPast ? ' ago' : '');
    }
}
