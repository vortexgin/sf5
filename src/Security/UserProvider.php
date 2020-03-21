<?php

namespace App\Security;

use App\Document\User;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    /**
     * @var UserRepository $_userRepo
     */
    private $_userRepo;

    /**
     * UserProvider constructor.
     */
    public function __construct(DocumentManager $dm)
    {
        $this->_userRepo = $dm->getRepository(User::class);
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername(string $username)
    {
        $user = $this->_userRepo->find($username);
        if (empty($user)) {
            $user = $this->_userRepo->findByEmailOrMobilePhone($username);
            if (empty($user)) {
                throw new BadCredentialsException('Your token doesn\'t contain the correct user');
            }
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return ($class instanceof UserInterface);
    }
}