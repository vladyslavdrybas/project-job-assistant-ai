<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\EmploymentRepository;
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
}
