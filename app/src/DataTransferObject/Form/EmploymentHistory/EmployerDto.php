<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EmploymentHistory;

use App\DataTransferObject\Form\Contact\ContactsDto;
use App\DataTransferObject\IDataTransferObject;

class EmployerDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?string $aboutPage = null,
        public ?ContactsDto $contacts = null
    ) {}
}
