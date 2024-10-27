<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\CoverLetterDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Achievement;
use App\Entity\CoverLetter;
use App\Entity\EntityInterface;

class CoverLetterTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = CoverLetter::class;
    protected const DTO_CLASS = CoverLetterDto::class;

    public function transform(CoverLetterDto|IDataTransferObject $dto): EntityInterface|CoverLetter
    {
        $this->validateDto($dto);

        /** @var CoverLetter $entity */
        $entity = $this->findEntityOrCreate($dto);

        dump($dto->sender);

        $entity->setOwner($dto->owner);
        $entity->setContent($dto->content);
        $entity->setTitle($dto->title);
        $entity->setJobTitle($dto->jobTitle);
        $entity->setEmployer($dto->employer);
        $entity->setSender($dto->sender);
        $entity->setReceiver($dto->receiver);
        $entity->setJobs($dto->jobs);

        return $entity;
    }

    public function reverseTransform(CoverLetter|EntityInterface $entity): IDataTransferObject|CoverLetterDto
    {
        $this->validateEntity($entity);

        $dto = new CoverLetterDto();

        $dto->owner = $entity->getOwner();
        $dto->content = $entity->getContent();
        $dto->promptTips = $entity->getPromptTips();
        $dto->promptFramework = $entity->getPromptFramework();
        $dto->title = $entity->getTitle();
        $dto->jobTitle = $entity->getJobTitle();
        $dto->employer = $entity->getEmployer();
        $dto->sender = $entity->getSender();
        $dto->receiver = $entity->getReceiver();

        $dto->id = $entity->getRawId();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();
        $dto->jobs = $entity->getJobs();

        return $dto;
    }
}
