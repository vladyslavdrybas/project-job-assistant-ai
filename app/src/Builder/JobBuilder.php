<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Form\Job\JobDto;
use App\Entity\Job;
use App\Entity\UserInterface;
use App\EntityTransformer\JobTransformer;

class JobBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly JobTransformer $transformer
    ) {}

    public function base(UserInterface $owner): Job
    {
        $entity = new Job();

        $entity->setOwner($owner);

        return $entity;
    }

    public function fromDto(JobDto $dto): ?Job
    {
        return $this->transformer->transform($dto);
    }
}
