<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\EducationHistory\EducationRecordDto;
use App\DataTransferObject\Form\EmploymentHistory\EmploymentRecordDto;
use App\DataTransferObject\Form\LanguageDto;
use App\DataTransferObject\Form\LinkDto;
use App\DataTransferObject\Form\ResumeDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\Resume;

class ResumeTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Resume::class;
    protected const DTO_CLASS = ResumeDto::class;

    public function transform(ResumeDto|IDataTransferObject $dto): EntityInterface|Resume
    {
        $this->validateDto($dto);

        /** @var Resume $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setOwner($dto->owner);
        $entity->setTitle($dto->title);
        $entity->setJobTitle($dto->jobTitle);
        $entity->setProfessionalSummary($dto->professionalSummary);
        $entity->setPersonalDetails($dto->personalDetails);
        $entity->setEmail($dto->personalDetails->contacts->email ?? null);

        return $entity;
    }

    public function reverseTransform(Resume|EntityInterface $entity): IDataTransferObject|ResumeDto
    {
        $this->validateEntity($entity);

        $dto = new ResumeDto();

        $dto->id = $entity->getRawId();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();
        $dto->owner = $entity->getOwner();
        $dto->title = $entity->getTitle();
        $dto->jobTitle = $entity->getJobTitle();
        $dto->professionalSummary = $entity->getProfessionalSummary();
        $dto->personalDetails = $entity->getPersonalDetails();

        $employmentHistory = [];
        for ($i = 0; $i < 22; $i++) {
            $employmentHistory[] = new EmploymentRecordDto();
        }
        $dto->employmentHistory = $employmentHistory;

        $educationHistory = [];
        for ($i = 0; $i < 8; $i++) {
            $educationHistory[] = new EducationRecordDto();
        }
        $dto->educationHistory = $educationHistory;

        $links = [];
        for ($i = 0; $i < 8; $i++) {
            $links[] = new LinkDto();
        }
        $dto->links = $links;

        $languages = [];
        for ($i = 0; $i < 10; $i++) {
            $languages[] = new LanguageDto();
        }
        $dto->languages = $languages;

        return $dto;
    }
}
