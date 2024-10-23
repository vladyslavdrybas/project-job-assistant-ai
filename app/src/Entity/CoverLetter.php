<?php
declare(strict_types=1);

namespace App\Entity;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\DataTransferObject\Form\EmploymentHistory\EmployerDto;
use App\Entity\Type\JsonDataTransferObjectType;
use App\Repository\CoverLetterRepository;
use App\Traits\Entity\EntityWithOwner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoverLetterRepository::class, readOnly: false)]
#[ORM\Table(name: "cover_letter")]
class CoverLetter extends AbstractEntity
{
    use EntityWithOwner;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $jobTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $promptTips = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $promptFramework = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?EmployerDto $employer = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?ContactPersonDto $sender = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?ContactPersonDto $receiver = null;

    /**
     * @var Collection<int, Job>
     */
    #[ORM\OneToMany(mappedBy: 'coverLetter', targetEntity: Job::class)]
    protected Collection $jobs;

    public function __construct()
    {
        parent::__construct();
        $this->jobs = new ArrayCollection();
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

    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): void
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setCoverLetter($this);
        }
    }

    public function removeJob(Job $job): void
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
            $job->setCoverLetter(null);
        }
    }

    public function setJobs(Collection $jobs): void
    {
        foreach ($jobs as $job) {
            $this->addJob($job);
        }
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    public function getPromptTips(): ?string
    {
        return $this->promptTips;
    }

    public function setPromptTips(?string $promptTips): void
    {
        $this->promptTips = $promptTips;
    }

    public function getPromptFramework(): ?string
    {
        return $this->promptFramework;
    }

    public function setPromptFramework(?string $promptFramework): void
    {
        $this->promptFramework = $promptFramework;
    }

    public function getEmployer(): ?EmployerDto
    {
        return $this->employer;
    }

    public function setEmployer(?EmployerDto $employer): void
    {
        $this->employer = $employer;
    }

    public function getSender(): ?ContactPersonDto
    {
        return $this->sender;
    }

    public function setSender(?ContactPersonDto $sender): void
    {
        $this->sender = $sender;
    }

    public function getReceiver(): ?ContactPersonDto
    {
        return $this->receiver;
    }

    public function setReceiver(?ContactPersonDto $receiver): void
    {
        $this->receiver = $receiver;
    }
}
