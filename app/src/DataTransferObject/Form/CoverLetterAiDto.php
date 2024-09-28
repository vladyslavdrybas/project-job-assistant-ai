<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;

class CoverLetterAiDto implements IDataTransferObject
{
    public function __construct(
        public ?UserInterface $owner = null,
        public ?CoverLetterDto $coverLetter = null,
        public ?ResumeDto $resume = null
    ) {}
}
