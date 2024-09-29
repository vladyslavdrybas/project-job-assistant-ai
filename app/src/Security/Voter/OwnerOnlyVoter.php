<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\EntityInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OwnerOnlyVoter extends AbstractVoter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof EntityInterface) {
            return false;
        }

        if (null === VoterPermissions::tryFrom($attribute)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $this->isOwner($user, $subject);
    }
}
