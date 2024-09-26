<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Form\ResumeDto;
use App\Entity\Resume;
use App\Entity\UserInterface;
use App\EntityTransformer\ResumeTransformer;

class ResumeBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly ResumeTransformer $resumeTransformer
    ) {}

    public function base(UserInterface $owner): Resume
    {
        $resume = new Resume();

        $resume->setOwner($owner);

        return $resume;
    }

    public function fromDto(ResumeDto $dto): ?Resume
    {
        return $this->resumeTransformer->transform($dto);
    }
}
