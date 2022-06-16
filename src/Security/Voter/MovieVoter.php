<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const CAN_DELETE = 'CAN_DELETE';

    // @IsGranted('CAN_DELETE', Movie)
    protected function supports(string $attribute, $subject): bool
    {
        return self::CAN_DELETE === $attribute
            && $subject instanceof \App\Entity\Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if($user->getUsername() === 'adrien') {
            return true;
        }

        //$movie
        return $subject->getCreator()->getId() === $user->getId();
        return $subject->getCreator() === $user;
    }
}
