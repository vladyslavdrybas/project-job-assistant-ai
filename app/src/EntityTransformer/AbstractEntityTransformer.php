<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

abstract class AbstractEntityTransformer implements EntityTransformerInterface
{
    protected const ENTITY_CLASS = EntityInterface::class;
    protected const DTO_CLASS = IDataTransferObject::class;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    public function supports(mixed $data): bool
    {
        $class = static::ENTITY_CLASS;
        $dto = static::DTO_CLASS;

        return $data instanceof $class || $data instanceof $dto;
    }

    protected function validateEntity(EntityInterface $entity): void
    {
        if (!$this->supports($entity)) throw new InvalidArgumentException('Expect ' . static::ENTITY_CLASS);
    }

    protected function validateDto(IDataTransferObject $dto): void
    {
        if (!$this->supports($dto)) throw new InvalidArgumentException('Expect ' . static::DTO_CLASS);
    }

    protected function findEntityOrCreate(IDataTransferObject $dto): EntityInterface
    {
        $class = static::ENTITY_CLASS;

        if (!isset($dto->id)) {
            return new $class;
        }

        if (empty($class)) {
            throw new InvalidArgumentException('Expect repository for ' . static::ENTITY_CLASS);
        }

        $entity = $this->entityManager->getRepository($class)->findOneBy([
            'id' => $dto->id
        ]);

        return $entity ?? new $class;
    }
}
