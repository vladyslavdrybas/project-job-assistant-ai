<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\CoverLetterDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\CoverLetter;
use App\Entity\EntityInterface;

class CoverLetterTransformer extends AbstractEntityTransformer
{
    public function supports(mixed $data): bool
    {
        return $data instanceof CoverLetter || $data instanceof CoverLetterDto;
    }

    public function transform(CoverLetterDto|IDataTransferObject $dto): EntityInterface|CoverLetter|null
    {
        if (!$this->supports($dto)) return null;

        $entity = new CoverLetter();

        $entity->setOwner($dto->owner);

        return $entity;
    }

    public function reverseTransform(CoverLetter|EntityInterface $entity): IDataTransferObject|CoverLetterDto|null
    {
        if (!$this->supports($entity)) return null;

        $dto = new CoverLetterDto();

        $dto->owner = $entity->getOwner();
        $dto->content = $entity->getContent();
        $dto->title = $entity->getTitle();

        return $dto;
    }
}
