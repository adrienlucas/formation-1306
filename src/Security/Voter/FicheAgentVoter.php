<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FicheAgentVoter extends Voter
{
    public const CAN_SHOW_FICHE = 'CAN_SHOW_FICHE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute === self::CAN_SHOW_FICHE
            && $subject instanceof \App\Entity\Agent;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Agent $subject */

        $subject->getEtablissement()->getId() !== $user->getEtablissement()->getId();


        return false;
    }
}
