<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\CoverLetterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoverLetterRepository::class, readOnly: false)]
#[ORM\Table(name: "cover_letter")]
class CoverLetter extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Resume must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: 'thumbnail_id', referencedColumnName: 'id')]
    protected ?Media $thumbnail = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $content = null;

//    #[ORM\OneToOne(targetEntity: Employer::class)]
//    #[ORM\JoinColumn(name: 'employer_id', referencedColumnName: 'id')]
//    protected ?Employer $employer = null;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getThumbnail(): ?Media
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?Media $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
