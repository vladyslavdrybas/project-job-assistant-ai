<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\Contact\ContactsDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\UserEmployer;
use Doctrine\ORM\EntityManagerInterface;

class UserEmployerTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = UserEmployer::class;
    protected const DTO_CLASS = EmployerDto::class;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected LocationTransformer $locationTransformer,
    ) {
        parent::__construct($entityManager);
    }

    public function transform(EmployerDto|IDataTransferObject $dto): EntityInterface|UserEmployer
    {
        $this->validateDto($dto);

        $entity = null;
        /** @var UserEmployer $entity */
        $class = static::ENTITY_CLASS;
        if (null !== $dto->id) {
            $entity = $this->entityManager->getRepository($class)
                ->find($dto->id)
            ;
        } else if (null !== $dto->title && null !== $dto->owner) {
            $entity = $this->entityManager->getRepository($class)
                ->findOneBy([
                    'owner' => $dto->owner,
                    'title' => $dto->title,
                ])
            ;
        }

        if (null === $entity) {
            $entity = new $class();
        }

        $location = $dto->contacts?->location;
        if (null !== $location) {
            $location = $this->locationTransformer->transform($dto->contacts->location);
        }

        $entity->setTitle($dto->title);
        $entity->setEmail($dto->contacts?->email);
        $entity->setPhone($dto->contacts?->phone);
        $entity->setAboutPage($dto->aboutPage);
        $entity->setOwner($dto->owner);
        $entity->setLink($dto->contacts?->link);
        $entity->setLocation($location);

        return $entity;
    }

    public function reverseTransform(UserEmployer|EntityInterface $entity): IDataTransferObject|EmployerDto
    {
        $this->validateEntity($entity);

        $dto = new EmployerDto();

        $location = $entity->getLocation();
        if (null !== $location) {
            $location = $this->locationTransformer->reverseTransform($location);
        }

        $dto->title = $entity->getTitle();
        $dto->aboutPage = $entity->getAboutPage();
        $dto->owner = $entity->getOwner();
        $dto->contacts = new ContactsDto(
            $entity->getEmail(),
            $entity->getPhone(),
            $entity->getLink(),
            $location
        );

        $dto->id = $entity->getRawId();
        $dto->updatedAt = $entity->getUpdatedAt();
        $dto->createdAt = $entity->getCreatedAt();

        return $dto;
    }
}
