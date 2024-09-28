<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;

// TODO add job connection
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
       public bool $isNeedAiHelp = false
    ) {}
}
