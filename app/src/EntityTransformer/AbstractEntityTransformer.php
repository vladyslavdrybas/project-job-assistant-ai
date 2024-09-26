<?php
declare(strict_types=1);

namespace App\EntityTransformer;

abstract class AbstractEntityTransformer implements EntityTransformerInterface
{
    abstract public function supports(mixed $data): bool;
}
