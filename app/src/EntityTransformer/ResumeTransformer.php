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
    public function supports(mixed $data): bool
    {
        return $data instanceof Resume || $data instanceof ResumeDto;
    }

    public function transform(ResumeDto|IDataTransferObject $dto): EntityInterface|Resume|null
    {
        if (!$this->supports($dto)) return null;

        $resume = new Resume();

        $resume->setOwner($dto->owner);

        return $resume;
    }

    public function reverseTransform(Resume|EntityInterface $entity): IDataTransferObject|ResumeDto|null
    {
        if (!$this->supports($entity)) return null;

        $dto = new ResumeDto();

        $dto->owner = $entity->getOwner();

        $employmentHistory = [
            new EmploymentRecordDto()
        ];
        $dto->employmentHistory = $employmentHistory;

        $educationHistory = [
            new EducationRecordDto()
        ];
        $dto->educationHistory = $educationHistory;

        $links = [
            new LinkDto()
        ];
        $dto->links = $links;

        $languages = [
            new LanguageDto()
        ];
        $dto->languages = $languages;

        return $dto;
    }
}
