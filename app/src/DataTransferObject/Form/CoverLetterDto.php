<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;

class CoverLetterDto implements IDataTransferObject
{
    public function __construct(
       public ?string $title = null,
       public ?UserInterface $owner = null,
       public ?string $content = null,
       public ?string $language = null,
       public bool $isNeedAiHelp = false
    ) {}
}
