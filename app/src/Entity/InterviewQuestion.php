<?php
declare(strict_types=1);

namespace App\Entity;

use App\Constants\InterviewQuestion\InterviewQuestionCategory;
use App\Repository\InterviewQuestionRepository;
use App\Traits\Entity\EntityWithOwner;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterviewQuestionRepository::class, readOnly: false)]
#[ORM\Table(name: "interview_question")]
class InterviewQuestion extends AbstractEntity
{
    use EntityWithOwner;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    protected string $hash;

    #[ORM\Column(type: Types::STRING, length: 1024, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 2048, nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: Types::STRING, enumType: InterviewQuestionCategory::class, options: ['default' => InterviewQuestionCategory::COMMON->value])]
    protected InterviewQuestionCategory $category = InterviewQuestionCategory::COMMON;

    #[ORM\Column(type: Types::STRING, length: 4096, nullable: true)]
    protected ?string $tips = null;

    #[ORM\Column(type: Types::STRING, length: 4096, nullable: true)]
    protected ?string $answerFramework = null;

    #[ORM\Column(type: Types::STRING, length: 2048, nullable: true)]
    protected ?string $answer = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $isDefault = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $isPublic = false;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
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

    public function getCategory(): InterviewQuestionCategory
    {
        return $this->category;
    }

    public function setCategory(InterviewQuestionCategory $category): void
    {
        $this->category = $category;
    }

    public function getTips(): ?string
    {
        return $this->tips;
    }

    public function setTips(?string $tips): void
    {
        $this->tips = $tips;
    }

    public function getAnswerFramework(): ?string
    {
        return $this->answerFramework;
    }

    public function setAnswerFramework(?string $answerFramework): void
    {
        $this->answerFramework = $answerFramework;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): void
    {
        $this->answer = $answer;
    }

    public function isDefault(): bool
    {
        return $this->isDefault && $this->isPublic && $this->owner === null;
    }

    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    public function canClone(UserInterface $owner): bool
    {
        return $this->isDefault() || ($this->isPublic() && $owner !== $this->owner);
    }
}
