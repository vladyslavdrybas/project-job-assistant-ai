<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\AchievementRepository;
use App\Traits\Entity\EntityWithOwner;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchievementRepository::class, readOnly: false)]
#[ORM\Table(name: "achievement")]
class Achievement extends AbstractEntity
{
    public const CONSTRAINT_DESCRIPTION_MAX_LENGTH = 2048;

    use EntityWithOwner;

    #[ORM\ManyToOne(targetEntity: Employment::class)]
    #[ORM\JoinColumn(name: 'employment_id', referencedColumnName: 'id')]
    protected ?Employment $employment = null;

    #[ORM\Column(name: "title", type: Types::STRING, length: 255, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(name: "description", type: Types::STRING, length: self::CONSTRAINT_DESCRIPTION_MAX_LENGTH, nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $doneAt = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $skills = [];

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(?array $skills): void
    {
        $this->skills = $skills;
    }

    public function getEmployment(): ?Employment
    {
        return $this->employment;
    }

    public function setEmployment(?Employment $employment): void
    {
        $this->employment = $employment;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDoneAt(): ?DateTimeImmutable
    {
        return $this->doneAt;
    }

    public function setDoneAt(?DateTimeImmutable $doneAt): void
    {
        $this->doneAt = $doneAt;
    }
}
