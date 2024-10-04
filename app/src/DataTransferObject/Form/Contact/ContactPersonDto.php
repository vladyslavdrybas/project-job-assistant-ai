<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;

use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Type\IDataTransferObjectType;
use App\Entity\UserInterface;
use DateTimeInterface;

class ContactPersonDto implements IDataTransferObject, IDataTransferObjectType
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?ContactsDto $contacts = null,
        public ?EmployerDto $employer = null,

        public ?string $id = null,
        public ?UserInterface $owner = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null
    ) {}

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'contacts' => $this->contacts?->__serialize(),
            'employer' => $this->employer?->__serialize(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->owner = $data['owner'] ?? null;
        $this->createdAt = $data['createdAt'] ?? null;
        $this->updatedAt = $data['updatedAt'] ?? null;
        $this->firstName = $data['first_name'] ?? null;
        $this->lastName = $data['last_name'] ?? null;
        $this->contacts = ContactsDto::fromArray($data['contacts'] ?? []);
        $this->employer = EmployerDto::fromArray($data['employer'] ?? []);
    }

    public function __toString(): string
    {
        return json_encode($this->__serialize(), JSON_THROW_ON_ERROR);
    }

    public static function fromArray(array $data): IDataTransferObjectType|ContactPersonDto
    {
        return new self(
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            contacts: ContactsDto::fromArray($data['contacts'] ?? []),
            employer: EmployerDto::fromArray($data['employer'] ?? []),
            id: $data['id'] ?? null,
            owner: $data['owner'] ?? null,
            createdAt: $data['createdAt'] ?? null,
            updatedAt: $data['updatedAt'] ?? null
        );
    }
}
