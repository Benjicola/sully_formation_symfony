<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MpaaVoter extends Voter
{
    const MOVIE_EDIT = 'MOVIE_EDIT';
    const MOVIE_VIEW = 'MOVIE_VIEW';

    public const MPAA_AGE_RESTRICTIONS = [
        'N/A' => 0,
        'G' => 0,
        'PG' => 0,
        'PG-13' => 13,
        'R' => 17,
        'NC-17' => 17,
    ];

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return \in_array($attribute, [self::MOVIE_EDIT, self::MOVIE_VIEW])
            && $subject instanceof \App\Entity\Movie;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var User $user */
        /** @var Movie $subject */

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::MOVIE_EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case self::MOVIE_VIEW:
                if ($user->getAge() > self::MPAA_AGE_RESTRICTIONS[$subject->getRated()]) {
                    return true;
                }
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
