<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Form\CoverLetterDto;
use App\Entity\CoverLetter;
use App\Entity\UserInterface;
use App\EntityTransformer\CoverLetterTransformer;

class CoverLetterBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly CoverLetterTransformer $transformer
    ) {}

    public function base(UserInterface $owner): CoverLetter
    {
        $entity = new CoverLetter();

        $entity->setOwner($owner);

        return $entity;
    }

    public function fromDto(CoverLetterDto $dto): ?CoverLetter
    {
        return $this->transformer->transform($dto);
    }
}
