<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\InterviewQuestion;

/**
 * @method InterviewQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterviewQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterviewQuestion[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method InterviewQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterviewQuestionRepository extends AbstractRepository
{
}
