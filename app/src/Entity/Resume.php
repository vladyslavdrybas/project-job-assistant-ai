<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ResumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResumeRepository::class, readOnly: false)]
#[ORM\Table(name: "resume")]
class Resume extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Resume must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[ORM\OneToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: 'pdf_media_id', referencedColumnName: 'id')]
    protected ?Media $pdf = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: 'thumbnail_id', referencedColumnName: 'id')]
    protected ?Media $thumbnail = null;

    #[ORM\Column(type: Types::STRING, nullable: true )]
    protected ?string $title = null;

    #[ORM\Column(type: Types::STRING, nullable: true )]
    protected ?string $professionalSummary = null;

    #[ORM\Column(type: Types::STRING, nullable: true )]
    protected ?string $jobTitle = null;

    #[ORM\Column(type: Types::STRING, length: 250, nullable: true )]
    protected ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    protected ?string $firstname = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    protected ?string $lastname = null;

    /**
     * Many Resumes have Many Skills.
     * @var Collection<int, Skill>
     */
    #[ORM\JoinTable(name: 'resume_skill')]
    #[ORM\JoinColumn(name: 'resume_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Skill::class)]
    protected Collection $skills;

    public function __construct()
    {
        parent::__construct();
        $this->skills = new ArrayCollection();
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getPdf(): ?Media
    {
        return $this->pdf;
    }

    public function setPdf(?Media $pdf): void
    {
        $this->pdf = $pdf;
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

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function setSkills(Collection $skills): void
    {
        $this->skills = $skills;
    }

    public function getProfessionalSummary(): ?string
    {
        return $this->professionalSummary;
    }

    public function setProfessionalSummary(?string $professionalSummary): void
    {
        $this->professionalSummary = $professionalSummary;
    }
}
