<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\Achievement\AchievementDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Achievement;
use App\Entity\EntityInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class AchievementTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Achievement::class;
    protected const DTO_CLASS = AchievementDto::class;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected EmploymentTransformer $employmentTransformer
    ) {
        parent::__construct($entityManager);
    }

    public function transform(AchievementDto|IDataTransferObject $dto): EntityInterface|Achievement
    {
        $this->validateDto($dto);

        /** @var Achievement $entity */
        $entity = $this->findEntityOrCreate($dto);

        $employment = $dto->employment;
        if (null !== $employment) {
            $employment = $this->employmentTransformer->transform($employment);
        }

        $entity->setUpdatedAt(new DateTime());

        $entity->setOwner($dto->owner);
        $entity->setDescription($dto->description);
        $entity->setDoneAt($dto->doneAt);
        $entity->setDescription($dto->description);
        $entity->setSkills($dto->skills);
        $entity->setEmployment($employment);

        return $entity;
    }

    public function reverseTransform(Achievement|EntityInterface $entity): IDataTransferObject|AchievementDto
    {
        $this->validateEntity($entity);

        $dto = new AchievementDto();

        $dto->id = $entity->getRawId();
        $dto->owner = $entity->getOwner();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        $employment = $entity->getEmployment();
        if (null !== $dto->employment) {
            $employment = $this->employmentTransformer->reverseTransform($employment);
        }

        $dto->title = $entity->getTitle();
        $dto->description = $entity->getDescription();
        $dto->employment = $employment;
        $dto->skills = $entity->getSkills();
        $dto->doneAt = $entity->getDoneAt();

        return $dto;
    }
}
