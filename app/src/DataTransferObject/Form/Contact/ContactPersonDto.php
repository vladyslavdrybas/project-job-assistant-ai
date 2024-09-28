<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;

use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;

class ContactPersonDto
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?ContactsDto $contacts = null,
        public ?EmployerDto $employer = null,
    ) {}
}
