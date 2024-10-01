<?php

namespace App\Repository;

use App\Entity\Employment;

/**
 * @method Employment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employment[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method Employment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmploymentRepository extends AbstractRepository
{
}
