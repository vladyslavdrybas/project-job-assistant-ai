<?php
declare(strict_types=1);

namespace App\Entity;

use App\Constants\Job\JobStatus;
use App\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JobRepository::class, readOnly: false)]
#[ORM\Table(name: "job")]
class Job extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $content = null;

    #[ORM\Column(type: Types::STRING, enumType: JobStatus::class, options: ['default' => JobStatus::NEW->value])]
    protected JobStatus $status = JobStatus::NEW;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $isUserAdded = false;

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
