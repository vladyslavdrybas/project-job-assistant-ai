<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;

interface EntityTransformerInterface
{
    public function transform(IDataTransferObject $dto): EntityInterface;
    public function reverseTransform(EntityInterface $entity): IDataTransferObject;
}
