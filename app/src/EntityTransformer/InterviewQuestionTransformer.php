<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\Constants\InterviewQuestion\InterviewQuestionCategory;
use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\InterviewQuestion;
use DateTime;

class InterviewQuestionTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = InterviewQuestion::class;
    protected const DTO_CLASS = InterviewQuestionDto::class;

    public function transform(InterviewQuestionDto|IDataTransferObject $dto): EntityInterface|InterviewQuestion
    {
        $this->validateDto($dto);

        /** @var InterviewQuestion $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setUpdatedAt(new DateTime());

        $entity->setOwner($dto->owner);
        $entity->setTitle($dto->title);
        $entity->setDescription($dto->description);
        $entity->setCategory(InterviewQuestionCategory::from($dto->category));
        $entity->setAnswer($dto->answer);
        $entity->setAnswerFramework($dto->answerFramework);
        $entity->setIsDefault($dto->isDefault);
        $entity->setIsPublic($dto->isPublic);
        $entity->setTips($dto->tips);
        $hash = $dto->title !== null ? md5($dto->title) : null;
        $entity->setHash($hash);

        return $entity;
    }

    public function reverseTransform(InterviewQuestion|EntityInterface $entity): IDataTransferObject|InterviewQuestionDto
    {
        $this->validateEntity($entity);

        $dto = new InterviewQuestionDto();

        $dto->id = $entity->getRawId();
        $dto->owner = $entity->getOwner();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        $dto->title = $entity->getTitle();
        $dto->description = $entity->getDescription();
        $dto->category = $entity->getCategory()->value;
        $dto->answerFramework = $entity->getAnswerFramework();
        $dto->answer = $entity->getAnswer();
        $dto->isDefault = $entity->isDefault();
        $dto->isPublic = $entity->isPublic();
        $dto->tips = $entity->getTips();
        $dto->hash = $entity->getHash();

        return $dto;
    }
}
