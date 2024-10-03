<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\EmploymentHistory;

use App\DataTransferObject\Form\Contact\ContactsDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Type\IDataTransferObjectType;
use App\Entity\UserInterface;
use DateTimeInterface;

class EmployerDto implements IDataTransferObject, IDataTransferObjectType
{
    public function __construct(
        public ?string $title = null,
        public ?string $aboutPage = null,
        public ?ContactsDto $contacts = null,

        public ?string $id = null,
        public ?UserInterface $owner = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}

    public function __serialize(): array
    {
        return [
            'title' => $this->title,
            'about_page' => $this->aboutPage,
            'contacts' => $this->contacts?->__serialize(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->title = $data['title'] ?? null;
        $this->aboutPage = $data['about_page'] ?? null;
        $this->contacts = ContactsDto::fromArray($data['contacts'] ?? []);
    }

    public function __toString(): string
    {
        return json_encode($this->__serialize(), JSON_THROW_ON_ERROR);
    }

    public static function fromArray(array $data): IDataTransferObjectType|EmployerDto
    {
        return new self(
            title: $data['title'] ?? null,
            aboutPage: $data['about_page'] ?? null,
            contacts: ContactsDto::fromArray($data['contacts'] ?? [])
        );
    }
}
