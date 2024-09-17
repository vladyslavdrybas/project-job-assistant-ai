<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\UuidV7;
use Symfony\Component\Serializer\Annotation\Groups;

use function array_pop;
use function explode;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    #[Groups(['base', 'main'])]
    #[ORM\Id]
    #[ORM\Column(name: "id", type: "uuid", unique: true)]
    protected UuidV7 $id;

    public function __construct()
    {
        $this->id = new UuidV7();
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function __toString(): string
    {
        return $this->id->toRfc4122();
    }

    #[Ignore]
    public function getObject(): string
    {
        $namespace = explode('\\', static::class);

        return array_pop($namespace);
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function setId(UuidV7 $id): void
    {
        $this->id = $id;
    }

    #[Ignore]
    public function getRawId(): string
    {
        return $this->id->toRfc4122();
    }

    #[Groups(['base', 'main'])]
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    #[Groups(['base', 'main'])]
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }
}
