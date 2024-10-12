<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\InterviewQuestion;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTimeInterface;

class InterviewQuestionDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?string $hash = null,
        public ?string $description = null,
        public ?string $category = null,
        public ?string $tips = null,
        public ?string $answerFramework = null,
        public ?string $answer = null,
        public bool $isDefault = false,
        public bool $isPublic = false,

        /** @var array<string> $examples*/
        public ?array $examples = [],

        public ?string $id = null,
        public ?UserInterface $owner = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null,
    ) {}
}
