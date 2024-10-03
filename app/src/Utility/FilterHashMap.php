<?php
declare(strict_types=1);

namespace App\Utility;

class FilterHashMap
{
    protected array $map = [];

    public function count(): int
    {
        return count($this->map);
    }

    public function countPositive(): int
    {
        return count(array_filter($this->map, fn(bool $isMatch) => $isMatch));
    }

    public function countNegative(): int
    {
        return count(array_filter($this->map, fn(bool $isMatch) => !$isMatch));
    }

    public function put(string $key, bool $value): void
    {
        $hashCode = $this->hashCode($key);
        $this->map[$hashCode] = $value;
    }

    public function get($key): ?bool
    {
        $hashCode = $this->hashCode($key);
        return $this->map[$hashCode] ?? null;
    }

    public function has($key): bool
    {
        $hashCode = $this->hashCode($key);
        return isset($this->map[$hashCode]);
    }

    public function hashCode(string $key): string|int
    {
        return hash('md2', strtolower($key));
    }
}
