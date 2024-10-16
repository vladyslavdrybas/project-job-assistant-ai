<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Achievement;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTimeImmutable;
use DateTimeInterface;

class AchievementDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?DateTimeImmutable $doneAt = null,
        public ?AchievementEmploymentDto $employment = null,

        /** @var array<string> $skills*/
        public ?array $skills = [],

        public ?string $id = null,
        public ?UserInterface $owner = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null,
    ) {}
}
