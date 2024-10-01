<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\EmploymentHistory\EmploymentRecordDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Employment;
use App\Entity\EntityInterface;
use DateTime;

class EmploymentTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Employment::class;
    protected const DTO_CLASS = EmploymentRecordDto::class;

    public function transform(EmploymentRecordDto|IDataTransferObject $dto): EntityInterface|Employment
    {
        $this->validateDto($dto);

        /** @var Employment $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setUpdatedAt(new DateTime());

        $entity->setOwner($dto->owner);
        $entity->setJobTitle($dto->jobTitle);
        $entity->setProjectTitle($dto->projectTitle);
        $entity->setDescription($dto->description);
        $entity->setStartDate($dto->startDate);
        $entity->setEndDate($dto->endDate);
        $entity->setFormats($dto->formats);
        $entity->setSkills($dto->skills);
        $entity->setEmployer($dto->employer);
        $entity->setContactPerson($dto->contactPerson);

        return $entity;
    }

    public function reverseTransform(Employment|EntityInterface $entity): IDataTransferObject|EmploymentRecordDto
    {
        $this->validateEntity($entity);

        $dto = new EmploymentRecordDto();

        $dto->id = $entity->getRawId();
        $dto->owner = $entity->getOwner();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        $dto->jobTitle = $entity->getJobTitle();
        $dto->projectTitle = $entity->getProjectTitle();
        $dto->description = $entity->getDescription();
        $dto->startDate = $entity->getStartDate();
        $dto->endDate = $entity->getEndDate();
        $dto->formats = $entity->getFormats();
        $dto->skills = $entity->getSkills();
        $dto->employer = $entity->getEmployer();
        $dto->contactPerson = $entity->getContactPerson();

        return $dto;
    }
}
