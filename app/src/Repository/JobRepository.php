<?php

namespace App\Repository;

use App\Constants\Job\JobStatus;
use App\Entity\Job;
use App\Entity\UserInterface;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends AbstractRepository
{
    public function findListForJobBoard(UserInterface $owner, ?JobStatus $status = null): array
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('t.createdAt', 'DESC')
        ;

        if ($status) {
            $query->andWhere('t.status = :status')
                ->setParameter('status', $status)
            ;
        } else {
            $query->andWhere('t.status <> :notStatus')
                ->setParameter('notStatus', JobStatus::ARCHIVED)
            ;
        }

        return $query->getQuery()->getResult();
    }
}
