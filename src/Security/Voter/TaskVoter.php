<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const MODIFY = 'TASK_MODIFY';

    protected function supports(string $attribute, mixed $task): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::MODIFY], true)
            && $task instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $task, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // Tasks attached to the “anonymous” user can be modified by users with the administrator role (ROLE_ADMIN).
        if (in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            return true;
        }
        // Tasks can be modified by users who created the task in question.
        if ($user === $task->getUser()) {
            return true;
        }

        return false;
    }
}
