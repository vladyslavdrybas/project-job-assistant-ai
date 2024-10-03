<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserEmployerRepository;

use App\Traits\Entity\EntityWithOwner;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEmployerRepository::class, readOnly: false)]
#[ORM\Table(name: "user_employer")]
class UserEmployer extends AbstractEntity
{
    use EntityWithOwner;

    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id')]
    protected ?Location $location = null;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $aboutPage = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $phone = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $link = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getAboutPage(): ?string
    {
        return $this->aboutPage;
    }

    public function setAboutPage(?string $aboutPage): void
    {
        $this->aboutPage = $aboutPage;
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
