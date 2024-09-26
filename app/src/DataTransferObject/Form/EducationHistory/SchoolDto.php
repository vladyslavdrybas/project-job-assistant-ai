<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EducationHistory;

use App\DataTransferObject\Form\Contact\ContactsDto;
use App\DataTransferObject\IDataTransferObject;

class SchoolDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?string $aboutPage = null,
        public ?ContactsDto $contacts = null
    ) {}
}
