<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\Contact\ContactsDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\UserEmployerContactPerson;
use Doctrine\ORM\EntityManagerInterface;

class UserEmployerContactPersonTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = UserEmployerContactPerson::class;
    protected const DTO_CLASS = ContactPersonDto::class;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected LocationTransformer $locationTransformer,
        protected UserEmployerTransformer $userEmployerTransformer,
    ) {
        parent::__construct($entityManager);
    }

    public function transform(ContactPersonDto|IDataTransferObject $dto): EntityInterface|UserEmployerContactPerson
    {
        $this->validateDto($dto);

        $entity = null;
        /** @var UserEmployerContactPerson $entity */
        $class = static::ENTITY_CLASS;
        if (null !== $dto->id) {
            $entity = $this->entityManager->getRepository($class)
                ->find($dto->id)
            ;
        } else if (null !== $dto->contacts->email && null !== $dto->owner) {
            $entity = $this->entityManager->getRepository($class)
                ->findOneBy([
                    'owner' => $dto->owner,
                    'email' => $dto->contacts->email,
                ])
            ;
        }

        if (null === $entity) {
            $entity = new $class();
        }

        $location = null;
        if (null !== $dto->contacts?->location) {
            $location = $this->locationTransformer->transform($dto->contacts->location);
        }

        $employer = null;
        if (null !== $dto->employer) {
            $employer = $this->userEmployerTransformer->transform($dto->employer);
        }

        $entity->setFirstName($dto->firstName);
        $entity->setLastName($dto->lastName);
        $entity->setEmail($dto->contacts?->email);
        $entity->setPhone($dto->contacts?->phone);
        $entity->setOwner($dto->owner);
        $entity->setLink($dto->contacts?->link);
        $entity->setLocation($location);
        $entity->setEmployer($employer);

        return $entity;
    }

    public function reverseTransform(UserEmployerContactPerson|EntityInterface $entity): IDataTransferObject|ContactPersonDto
    {
        $this->validateEntity($entity);

        $dto = new ContactPersonDto();

        $location = $entity->getLocation();
        if (null !== $location) {
            $location = $this->locationTransformer->reverseTransform($location);
        }

        $employer = $entity->getEmployer();
        if (null !== $employer) {
            $employer = $this->userEmployerTransformer->reverseTransform($employer);
            $dto->employer = $employer;
        }

        $dto->firstName = $entity->getFirstName();
        $dto->lastName = $entity->getLastName();
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
