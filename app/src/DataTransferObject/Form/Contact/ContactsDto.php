<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;
use App\DataTransferObject\IDataTransferObject;
use Symfony\Component\Validator\Constraints as Assert;

class ContactsDto implements IDataTransferObject
{
    public function __construct(
        #[Assert\Email]
        public ?string $email = null,
        #[Assert\PositiveOrZero]
        public ?string $phone = null,
        public ?LocationDto $location = null
    ) {}
}
