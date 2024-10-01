<?php
declare(strict_types=1);

namespace App\Entity;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\Entity\Type\JsonDataTransferObjectType;
use App\Repository\EmploymentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmploymentRepository::class, readOnly: false)]
#[ORM\Table(name: "employment")]
class Employment extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $jobTitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $projectTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $endDate = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $formats = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $skills = [];

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?EmployerDto $employer = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?ContactPersonDto $contactPerson = null;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    public function getProjectTitle(): ?string
    {
        return $this->projectTitle;
    }

    public function setProjectTitle(?string $projectTitle): void
    {
        $this->projectTitle = $projectTitle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getFormats(): ?array
    {
        return $this->formats;
    }

    public function setFormats(?array $formats = []): void
    {
        $this->formats = $formats;
    }

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(?array $skills = []): void
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
}
