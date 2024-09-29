<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Job;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTimeInterface;

class JobDto implements IDataTransferObject
{
    public function __construct(
        public ?UserInterface $owner = null,
        public ?string $title = null,
        public ?string $content = null,
        public bool $isUserAdded = false,
        public ?string $id = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}
}
