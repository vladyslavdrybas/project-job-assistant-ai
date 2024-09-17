<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\RequestCallBackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RequestCallBackRepository::class, readOnly: false)]
#[ORM\Table(name: "request_call_back")]
#[UniqueEntity('hash')]
class RequestCallBack extends AbstractEntity
{
    #[Groups(['main'])]
    #[ORM\Column(type: Types::STRING, length: 64, unique: true, nullable: false)]
    protected string $hash;

    #[Assert\Email]
    #[Assert\NotBlank]
    #[Groups(['main', 'ap-table'])]
    #[ORM\Column(type: Types::STRING, length: 180, nullable: false)]
    protected string $email;

    #[Assert\NotBlank]
    #[Groups(['main', 'ap-table'])]
    #[ORM\Column(type: Types::STRING, length: 180, nullable: false)]
    protected string $name;

    #[Assert\NotBlank]
    #[Groups(['main', 'ap-table'])]
    #[ORM\Column(type: Types::STRING, length: 4096, nullable: false)]
    protected string $description;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
