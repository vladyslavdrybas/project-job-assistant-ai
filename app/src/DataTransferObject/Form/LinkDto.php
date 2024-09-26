<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\IDataTransferObject;

class LinkDto implements IDataTransferObject
{
    public function __construct(
       public ?string $name = null,
       public ?string $url = null
    ) {}
}
