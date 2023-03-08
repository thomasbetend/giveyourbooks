<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ConversationVoter extends Voter
{
    public const CONVERSATION_OWNER = 'CONVERSATION_OWNER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CONVERSATION_OWNER])
            && $subject instanceof \App\Entity\Conversation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $user == $subject->getUser();
    }
}
