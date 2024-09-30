<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\Job\JobDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\Job;

class JobTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Job::class;
    protected const DTO_CLASS = JobDto::class;

    public function transform(JobDto|IDataTransferObject $dto): EntityInterface|Job
    {
        $this->validateDto($dto);

        /** @var Job $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setOwner($dto->owner);
        $entity->setTitle($dto->title);
        $entity->setContent($dto->content);
        $entity->setIsUserAdded($dto->isUserAdded);
        $entity->setStatus($dto->status);

        return $entity;
    }

    public function reverseTransform(Job|EntityInterface $entity): IDataTransferObject|JobDto
    {
        $this->validateEntity($entity);

        $dto = new JobDto();

        $dto->owner = $entity->getOwner();
        $dto->title = $entity->getTitle();
        $dto->content = $entity->getContent();
        $dto->isUserAdded = $entity->isUserAdded();
        $dto->id = $entity->getRawId();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();
        $dto->status = $entity->getStatus();

        return $dto;
    }
}
