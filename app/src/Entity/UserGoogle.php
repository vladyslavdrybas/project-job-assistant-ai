<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserGoogleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserGoogleRepository::class, readOnly: false)]
#[ORM\Table(name: "user_google")]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email.')]
#[UniqueEntity(fields: ['googleId'], message: 'There is already an account with this googleId.')]
class UserGoogle extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Google must be connected to user.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected User $owner;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, unique: true, nullable: false)]
    protected string $googleId;

    #[Assert\Email]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, unique: true, nullable: false)]
    protected string $email;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
    protected ?string $fullName = null;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
    protected ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $avatar = null;

    #[ORM\Column(type: 'boolean', options: ["default" => false] )]
    protected bool $isEmailVerified = false;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $locale = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $hostedDomain = null;

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getGoogleId(): string
    {
        return $this->googleId;
    }

    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function isEmailVerified(): bool
    {
        return $this->isEmailVerified;
    }

    public function setIsEmailVerified(bool $isEmailVerified): void
    {
        $this->isEmailVerified = $isEmailVerified;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    public function getHostedDomain(): ?string
    {
        return $this->hostedDomain;
    }

    public function setHostedDomain(?string $hostedDomain): void
    {
        $this->hostedDomain = $hostedDomain;
    }
}
