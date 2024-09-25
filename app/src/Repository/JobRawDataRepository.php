<?php

namespace App\Repository;

use App\Entity\AbstractJobRawData;

/**
 * @method AbstractJobRawData|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractJobRawData|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractJobRawData[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method AbstractJobRawData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRawDataRepository extends AbstractRepository
{
}
