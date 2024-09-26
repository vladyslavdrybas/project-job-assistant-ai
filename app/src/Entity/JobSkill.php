<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\JobSkillRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: JobSkillRepository::class, readOnly: false)]
#[ORM\Table(name: "job_skill")]
#[UniqueEntity(fields: ['title'], message: 'Skill exists.')]
class JobSkill extends AbstractEntity
{
    #[ORM\Column(type: Types::STRING, unique: true, nullable: false)]
    protected string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
