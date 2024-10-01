<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\Type\IDataTransferObjectType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactsDto implements IDataTransferObject, IDataTransferObjectType
{
    public function __construct(
        #[Assert\Email]
        public ?string $email = null,
        #[Assert\PositiveOrZero]
        public ?string $phone = null,
        #[Assert\Url]
        public ?string $link = null,
        public ?LocationDto $location = null
    ) {}

    public function __serialize(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
            'link' => $this->link,
            'location' => $this->location?->__serialize(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->link = $data['link'] ?? null;
        $this->location = LocationDto::fromArray($data['location'] ?? []);
    }

    public function __toString(): string
    {
        return json_encode($this->__serialize(), JSON_THROW_ON_ERROR);
    }

    public static function fromArray(array $data): IDataTransferObjectType|ContactsDto
    {
        return new self(
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            link: $data['link'] ?? null,
            location: LocationDto::fromArray($data['location'] ?? []),
        );
    }
}
