<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserEmployerContactPersonRepository;
use App\Traits\Entity\EntityWithOwner;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEmployerContactPersonRepository::class, readOnly: false)]
#[ORM\Table(name: "user_employer_contact_person")]
class UserEmployerContactPerson extends AbstractEntity
{
    use EntityWithOwner;

    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id')]
    protected ?Location $location = null;

    #[ORM\ManyToOne(targetEntity: UserEmployer::class)]
    #[ORM\JoinColumn(name: 'user_employer_id', referencedColumnName: 'id')]
    protected ?UserEmployer $employer = null;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $phone = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $link = null;

    public function getEmployer(): ?UserEmployer
    {
        return $this->employer;
    }

    public function setEmployer(?UserEmployer $employer): void
    {
        $this->employer = $employer;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }
}
