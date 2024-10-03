<?php
declare(strict_types=1);

namespace App\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagToStringTransformer implements DataTransformerInterface
{
    public const DIVIDER = ',';
    public const REPLACE_TEMPLATE = '[^-\s\.\w:_#+&]+';
    protected string $replaceTemplate = self::REPLACE_TEMPLATE;

    public function transform(mixed $value): string
    {
        if (is_string($value)) {
            return $this->purify($value);
        }

        if ($value instanceof ArrayCollection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            return $this->purify(implode(static::DIVIDER, $value));
        }

        return '';
    }

    public function reverseTransform(mixed $value): array
    {
        if (!is_string($value)) {
            return [];
        }

        $value = array_unique(explode(static::DIVIDER, $value));
        $value = array_map(fn(string $value): string => $this->purify($value), $value);

        return $value;
    }

    protected function purify(string $value): string
    {
        return preg_replace('/' . $this->replaceTemplate . '/mui', '', trim($value));
    }

    public function setReplaceTemplate(string $template): self
    {
        $this->replaceTemplate = $template;

        return $this;
    }
}
