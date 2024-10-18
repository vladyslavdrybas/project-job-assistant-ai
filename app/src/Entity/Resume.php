<?php
declare(strict_types=1);

namespace App\Entity;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\Entity\Type\JsonDataTransferObjectType;
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

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?ContactPersonDto $personalDetails = null;

    #[ORM\Column(type: Types::STRING, nullable: true )]
    protected ?string $professionalSummary = null;

    #[ORM\Column(type: Types::STRING, nullable: true )]
    protected ?string $jobTitle = null;

    #[ORM\Column(type: Types::STRING, length: 250, nullable: true )]
    protected ?string $email = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $formats = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $skills = [];

    /**
     * Many Resumes have Many Skills.
     * @var Collection<int, Skill>
     */
    #[ORM\JoinTable(name: 'resume_skill')]
    #[ORM\JoinColumn(name: 'resume_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Skill::class)]
    protected Collection $filterSkills;

    /**
     * @var Collection<int, Job>
     */
    #[ORM\OneToMany(mappedBy: 'resume', targetEntity: Job::class)]
    protected Collection $jobs;

    public function __construct()
    {
        parent::__construct();
        $this->filterSkills = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    public function getFilterSkills(): Collection
    {
        return $this->filterSkills;
    }

    public function addFilterSkill(Skill $skill): void
    {
        if (!$this->filterSkills->contains($skill)) {
            $this->filterSkills->add($skill);
        }
    }

    public function removeFilterSkill(Skill $skill): void
    {
        if ($this->filterSkills->contains($skill)) {
            $this->filterSkills->removeElement($skill);
        }
    }

    public function setFilterSkills(Collection $skills): void
    {
        foreach ($skills as $skill) {
            $this->addFilterSkill($skill);
        }
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

    public function getProfessionalSummary(): ?string
    {
        return $this->professionalSummary;
    }

    public function setProfessionalSummary(?string $professionalSummary): void
    {
        $this->professionalSummary = $professionalSummary;
    }

    public function getFormats(): ?array
    {
        return $this->formats;
    }

    public function setFormats(?array $formats): void
    {
        $this->formats = $formats;
    }

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(?array $skills): void
    {
        $this->skills = $skills;
    }

    public function getPersonalDetails(): ?ContactPersonDto
    {
        return $this->personalDetails;
    }

    public function setPersonalDetails(?ContactPersonDto $personalDetails): void
    {
        $this->personalDetails = $personalDetails;
    }

    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): void
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
        }
    }

    public function removeJob(Job $job): void
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
        }
    }

    public function setJobs(Collection $jobs): void
    {
        foreach ($jobs as $job) {
            $this->addJob($job);
        }
    }
}
