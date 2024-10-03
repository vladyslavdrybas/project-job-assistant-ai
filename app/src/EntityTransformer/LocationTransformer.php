<?php
declare(strict_types=1);

namespace App\EntityTransformer;

use App\DataTransferObject\Form\Contact\LocationDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\EntityInterface;
use App\Entity\Location;

class LocationTransformer extends AbstractEntityTransformer
{
    protected const ENTITY_CLASS = Location::class;
    protected const DTO_CLASS = LocationDto::class;

    public function transform(LocationDto|IDataTransferObject $dto): EntityInterface|Location
    {
        $this->validateDto($dto);

        if (null === $dto->id) {
            $hash = $dto->hash ?? hash(
                'sha256',
                $dto->country
                . $dto->city
                . $dto->region
                . $dto->address
                . $dto->postalCode
                . $dto->latitude
                . $dto->longitude
            );
            $dto->id = $hash;
        }

        /** @var Location $entity */
        $entity = $this->findEntityOrCreate($dto);

        $entity->setId($dto->id);
        $entity->setCountry($dto->country);
        $entity->setCity($dto->city);
        $entity->setRegion($dto->region);
        $entity->setAddress($dto->address);
        $entity->setPostalCode($dto->postalCode);
        $entity->setLatitude($dto->latitude);
        $entity->setLongitude($dto->longitude);

        return $entity;
    }

    public function reverseTransform(Location|EntityInterface $entity): IDataTransferObject|LocationDto
    {
        $this->validateEntity($entity);

        $dto = new LocationDto();

        $dto->id = $entity->getRawId();

        $dto->country = $entity->getCountry();
        $dto->city = $entity->getCity();
        $dto->region = $entity->getRegion();
        $dto->address = $entity->getAddress();
        $dto->postalCode = $entity->getPostalCode();
        $dto->latitude = $entity->getLatitude();
        $dto->longitude = $entity->getLongitude();

        return $dto;
    }
}
