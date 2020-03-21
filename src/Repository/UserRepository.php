<?php

namespace App\Repository;

use App\Document\User;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends BaseRepository
{

    /**
     * @param string $search
     * @return array|object|null
     */
    public function findByEmailOrMobilePhone(string $search)
    {
        $qb = $this->createQueryBuilder()
            ->where(sprintf('isActive = %s', 1));
        $qb->addOr(
            $qb->expr()
                ->field('email')
                ->equals($search)
        )
        ->addOr(
            $qb->expr()
                ->field('mobilePhone')
                ->equals($search)
        );

        return $qb->getQuery()->getSingleResult();
    }

    public function matchingPassword(User $user, $password)
    {
        $compareUser = new User();
        $compareUser->setPlainPassword($password, getenv('APP_SECRET'));

        return $compareUser->getPassword() == $user->getPassword();
    }
}