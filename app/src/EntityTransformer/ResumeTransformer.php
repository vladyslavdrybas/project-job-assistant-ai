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
use InvalidArgumentException;

class ResumeTransformer extends AbstractEntityTransformer
{
    public function supports(mixed $data): bool
    {
        return $data instanceof Resume || $data instanceof ResumeDto;
    }

    public function transform(ResumeDto|IDataTransferObject $dto): EntityInterface|Resume
    {
        if (!$this->supports($dto)) return throw new InvalidArgumentException('Expect ' . ResumeDto::class);

        $resume = new Resume();

        $resume->setOwner($dto->owner);

        return $resume;
    }

    public function reverseTransform(Resume|EntityInterface $entity): IDataTransferObject|ResumeDto
    {
        if (!$this->supports($entity)) return throw new InvalidArgumentException('Expect ' . Resume::class);

        $dto = new ResumeDto();

        $dto->id = $entity->getRawId();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();
        $dto->owner = $entity->getOwner();
        $dto->title = $entity->getTitle();
        $dto->jobTitle = $entity->getJobTitle();
        $dto->professionalSummary = $entity->getProfessionalSummary();

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
