<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;

class ClientRequestCallBackDto
{
    public function __construct(
        public ?string $clientName = null,
        public ?string $clientEmail = null,
        public ?string $projectDescription = null,
    ) {}
}
