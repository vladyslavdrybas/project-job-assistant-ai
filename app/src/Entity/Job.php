<?php
declare(strict_types=1);

namespace App\Entity;

use App\Constants\Job\JobSalaryPeriod;
use App\Constants\Job\JobStatus;
use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\Entity\Type\JsonDataTransferObjectType;
use App\Repository\JobRepository;
use App\Traits\Entity\EntityWithOwner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class, readOnly: false)]
#[ORM\Table(name: "job")]
class Job extends AbstractEntity
{
    use EntityWithOwner;

    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id')]
    protected ?Location $location = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $aboutPage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $content = null;

    #[ORM\Column(type: Types::STRING, enumType: JobStatus::class, options: ['default' => JobStatus::BACKLOG->value])]
    protected JobStatus $status = JobStatus::BACKLOG;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $isUserAdded = false;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $formats = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $skills = [];

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?EmployerDto $employer = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?ContactPersonDto $contactPerson = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected ?int $salaryMin = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected ?int $salaryMax = null;

    #[ORM\Column(type: Types::STRING, nullable: true, enumType: JobSalaryPeriod::class)]
    protected ?JobSalaryPeriod $salaryPeriod = null;

    /**
     * Many Jobs have Many Skills.
     * @var Collection<int, Skill>
     */
    #[ORM\JoinTable(name: 'job_skill')]
    #[ORM\JoinColumn(name: 'job_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Skill::class)]
    protected Collection $filterSkills;

    public function __construct()
    {
        parent::__construct();
        $this->filterSkills = new ArrayCollection();
    }

    public function getSalaryMin(): ?int
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(?int $salaryMin): void
    {
        $this->salaryMin = $salaryMin;
    }

    public function getSalaryMax(): ?int
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(?int $salaryMax): void
    {
        $this->salaryMax = $salaryMax;
    }

    public function getSalaryPeriod(): ?JobSalaryPeriod
    {
        return $this->salaryPeriod;
    }

    public function setSalaryPeriod(?JobSalaryPeriod $salaryPeriod): void
    {
        $this->salaryPeriod = $salaryPeriod;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getAboutPage(): ?string
    {
        return $this->aboutPage;
    }

    public function setAboutPage(?string $aboutPage): void
    {
        $this->aboutPage = $aboutPage;
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

    public function getEmployer(): ?EmployerDto
    {
        return $this->employer;
    }

    public function setEmployer(?EmployerDto $employer): void
    {
        $this->employer = $employer;
    }

    public function getContactPerson(): ?ContactPersonDto
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?ContactPersonDto $contactPerson): void
    {
        $this->contactPerson = $contactPerson;
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

    public function isUserAdded(): bool
    {
        return $this->isUserAdded;
    }

    public function setIsUserAdded(bool $isUserAdded): void
    {
        $this->isUserAdded = $isUserAdded;
    }

    public function getStatus(): JobStatus
    {
        return $this->status;
    }

    public function setStatus(JobStatus $status): void
    {
        $this->status = $status;
    }
}
