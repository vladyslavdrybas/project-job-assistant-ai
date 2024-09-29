<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\IDataTransferObject;

class CoverLetterContentDto implements IDataTransferObject
{
    public function __construct(
       public ?string $opening = null,
       public ?string $whyMe = null,
       public ?string $conclusion = null,
       public ?string $signoff = null,
    ) {}
}
