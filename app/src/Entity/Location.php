<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class, readOnly: false)]
#[ORM\Table(name: "location")]
class Location implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 128, unique: true)]
    protected string $id;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    protected ?string $country = null;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    protected ?string $city = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $postalCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $address = null;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    protected ?string $region = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $latitude = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $longitude = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getObject(): string
    {
        $namespace = explode('\\', static::class);

        return array_pop($namespace);
    }

    public function getRawId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->getRawId();
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }
}
