<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaCreatorFormDto
{
    public function __construct(
        public ?UploadedFile $file = null
    ) {}
}
