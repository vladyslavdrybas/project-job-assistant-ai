<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\Constants\Job\JobSalaryPeriod;
use App\DataTransferObject\Form\Job\JobDto;
use App\DataTransferObject\Form\Job\SalaryDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\Job;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class JobTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Job::class;
    protected const DTO_CLASS = JobDto::class;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected LocationTransformer $locationTransformer
    ) {
        parent::__construct($entityManager);
    }

    public function transform(JobDto|IDataTransferObject $dto): EntityInterface|Job
    {
        $this->validateDto($dto);

        /** @var Job $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setUpdatedAt(new DateTime());

        $location = null;
        if (null !== $dto->location) {
            $location = $this->locationTransformer->transform($dto->location);
        }

        $entity->setOwner($dto->owner);
        $entity->setTitle($dto->title);
        $entity->setContent($dto->content);
        $entity->setIsUserAdded($dto->isUserAdded);
        $entity->setStatus($dto->status);
        $entity->setLocation($location);
        $entity->setAboutPage($dto->aboutPage);
        $entity->setFormats($dto->formats);
        $entity->setSkills($dto->skills);
        $entity->setEmployer($dto->employer);
        $entity->setContactPerson($dto->contactPerson);
        $entity->setSalaryMin($dto->salary->min);
        $entity->setSalaryMax($dto->salary->max);

        $entity->setSalaryPeriod(JobSalaryPeriod::fromName($dto->salary->period));

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

        $location = $entity->getLocation();
        if (null !== $location) {
            $location = $this->locationTransformer->reverseTransform($location);
        }
        $dto->location = $location;

        $dto->aboutPage = $entity->getAboutPage();
        $dto->formats = $entity->getFormats();
        $dto->skills = $entity->getSkills();
        $dto->employer = $entity->getEmployer();
        $dto->contactPerson = $entity->getContactPerson();
        $dto->salary = new SalaryDto(
            $entity->getSalaryMin(),
            $entity->getSalaryMax(),
            $entity->getSalaryPeriod()?->name
        );

        return $dto;
    }
}
