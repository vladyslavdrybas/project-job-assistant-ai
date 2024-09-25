<?php
declare(strict_types=1);

namespace App\Entity;


use App\Repository\JobRawDataRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRawDataRepository::class, readOnly: false)]
#[ORM\Table(name: "job_raw_data")]
abstract class AbstractJobRawData extends AbstractEntity
{
    #[ORM\Column(type: Types::STRING)]
    protected string $resource;

    #[ORM\Column(type: Types::TEXT)]
    protected string $rawData;

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setResource(string $resource): void
    {
        $this->resource = $resource;
    }

    public function getRawData(): string
    {
        return $this->rawData;
    }

    public function setRawData(string $rawData): void
    {
        $this->rawData = $rawData;
    }
}
