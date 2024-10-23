<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CoverLetterDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?string $jobTitle = null,
        public ?UserInterface $owner = null,
        public ?string $content = null,
        public ?string $language = null,
        public ?EmployerDto $employer = null,
        public ?ContactPersonDto $sender = null,
        public ?ContactPersonDto $receiver = null,

        public ?string $id = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null,
        public ?Collection $jobs = null,

        public ?string $promptTips = null,
        public ?string $promptFramework = null
    ) {
        if (null === $jobs) {
            $this->jobs = new ArrayCollection();
        }
    }
}
