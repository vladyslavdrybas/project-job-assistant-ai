<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\InterviewQuestion;
use App\Entity\UserInterface;

/**
 * @method InterviewQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterviewQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterviewQuestion[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method InterviewQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterviewQuestionRepository extends AbstractRepository
{
    public function findDefaults(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isDefault = true')
            ->andWhere('t.isPublic = true')
            ->andWhere('t.owner IS NULL')
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByUser(UserInterface $user): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isDefault = false')
            ->andWhere('t.owner = :owner')
            ->setParameter('owner', $user)
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
