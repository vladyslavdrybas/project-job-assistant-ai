<?php

namespace App\Repository;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use App\Entity\UserEmployerContactPerson;

/**
 * @method UserEmployerContactPerson|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEmployerContactPerson|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEmployerContactPerson[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method UserEmployerContactPerson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEmployerContactPersonRepository extends AbstractRepository
{
    public function findOneByDto(ContactPersonDto $dto): ?UserEmployerContactPerson
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.owner = :owner')
            ->andWhere('t.email = :email OR t.phone = :phone OR t.link = :link')
            ->setParameter('owner', $dto->owner)
            ->setParameter('email', $dto->contacts->email)
            ->setParameter('phone', $dto->contacts->phone)
            ->setParameter('link', $dto->contacts->link)
            ->orderBy('t.createdAt', 'DESC')
        ;

        return $query->getQuery()->getOneOrNullResult();
    }
}
