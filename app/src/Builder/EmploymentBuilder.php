<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Form\EmploymentHistory\EmploymentRecordDto;
use App\Entity\Employment;
use App\Entity\UserInterface;
use App\EntityTransformer\EmploymentTransformer;

class EmploymentBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly EmploymentTransformer $transformer
    ) {}

    public function base(UserInterface $owner): Employment
    {
        $entity = new Employment();

        $entity->setOwner($owner);

        return $entity;
    }

    public function fromDto(EmploymentRecordDto $dto): ?Employment
    {
        return $this->transformer->transform($dto);
    }
}
