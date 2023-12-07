<?php

namespace App\Security\Voter;

use App\Entity\Seed;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SeedVoter extends Voter
{
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::SHOW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Seed) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Seed $seed */
        $seed = $subject;

        return match ($attribute) {
            self::SHOW => $this->canShow($seed, $user),
            self::EDIT => $this->canEdit($seed, $user),
            self::DELETE => $this->canDelete($seed, $user),
            default => throw new \LogicException('What are you trying to do ?'),
        };
    }

    private function canShow(Seed $seed, User $user): bool
    {
        return $this->canEdit($seed, $user);
    }

    private function canEdit(Seed $seed, User $user): bool
    {
        return $user->getId() === $seed->getOwner()->getId();
    }

    private function canDelete(Seed $seed, User $user): bool
    {
        return $this->canEdit($seed, $user);
    }
}
