<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form\Contact;

use App\DataTransferObject\IDataTransferObject;
use App\Entity\Type\IDataTransferObjectType;

class LocationDto implements IDataTransferObject, IDataTransferObjectType
{
    public function __construct(
        public ?string $id = null,
        public ?string $country = null,
        public ?string $city = null,
        public ?string $postalCode = null,
        public ?string $address = null,
        public ?string $region = null,
        public ?string $latitude = null,
        public ?string $longitude = null
    ){}

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'country' => $this->country,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'region' => $this->region,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->postalCode = $data['postal_code'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->region = $data['region'] ?? null;
        $this->latitude = $data['latitude'] ?? null;
        $this->longitude = $data['longitude'] ?? null;
    }

    public function __toString(): string
    {
        return json_encode($this->__serialize(), JSON_THROW_ON_ERROR);
    }

    public static function fromArray(array $data): IDataTransferObjectType|LocationDto
    {
        return new self(
            id: $data['id'] ?? null,
            country: $data['country'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            address: $data['address'] ?? null,
            region: $data['region'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null
        );
    }
}
