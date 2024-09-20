<?php

namespace App\Repository;

use App\Entity\UserGoogle;

/**
 * @method UserGoogle|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGoogle|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGoogle[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method UserGoogle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGoogleRepository extends AbstractRepository
{
    public function findByEmail(string $email): ?UserGoogle
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.email = :email')
            ->setParameter('email', $email);

        $result = $query->getQuery()->getOneOrNullResult();
        if ($result instanceof UserGoogle) {
            return $result;
        }

        return null;
    }
}
