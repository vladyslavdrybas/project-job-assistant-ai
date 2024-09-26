<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;

use App\DataTransferObject\IDataTransferObject;

class LocationDto implements IDataTransferObject
{
    public function __construct(
        public ?string $country = null,
        public ?string $city = null,
        public ?string $postalCode = null,
        public ?string $state = null,
        public ?string $latitude = null,
        public ?string $longitude = null
    ){}
}
