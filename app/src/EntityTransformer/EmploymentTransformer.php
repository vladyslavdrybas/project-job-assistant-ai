<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\EmploymentHistory\EmploymentRecordDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Employment;
use App\Entity\EntityInterface;

class EmploymentTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Employment::class;
    protected const DTO_CLASS = EmploymentRecordDto::class;

    public function transform(EmploymentRecordDto|IDataTransferObject $dto): EntityInterface|Employment
    {
        $this->validateDto($dto);

        /** @var Employment $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setOwner($dto->owner);
        $entity->setJobTitle($dto->jobTitle);
        $entity->setProjectTitle($dto->projectTitle);

        return $entity;
    }

    public function reverseTransform(Employment|EntityInterface $entity): IDataTransferObject|EmploymentRecordDto
    {
        $this->validateEntity($entity);

        $dto = new EmploymentRecordDto();

        $dto->owner = $entity->getOwner();
        $dto->jobTitle = $entity->getJobTitle();
        $dto->projectTitle = $entity->getProjectTitle();
        $dto->id = $entity->getRawId();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        return $dto;
    }
}
