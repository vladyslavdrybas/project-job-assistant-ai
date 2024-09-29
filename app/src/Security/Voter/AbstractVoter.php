<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function method_exists;

abstract class AbstractVoter extends Voter
{
    protected function isOwner(
        User $user,
        mixed $subject
    ): bool {
        if (method_exists($subject, 'getOwner')) {
            return $subject->getOwner() == $user;
        }

        return false;
    }
}
