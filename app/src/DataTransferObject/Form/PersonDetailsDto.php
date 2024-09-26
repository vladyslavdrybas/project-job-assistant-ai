<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\IDataTransferObject;
use DateTime;

class PersonDetailsDto implements IDataTransferObject
{
    public function __construct(
       public ?string $drivingLicense,
       public ?string $nationality,
       public ?string $gender,
       public ?DateTime $dateOfBirth = null,
       public ?string $placeOfBirth = null,
    ) {}
}
