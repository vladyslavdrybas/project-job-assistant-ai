<?php
declare(strict_types=1);

namespace App\DataTransferObject;

use DateTimeInterface;

class DocumentLinkDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title,
        public ?string $link,

        public ?string $id = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}
}
