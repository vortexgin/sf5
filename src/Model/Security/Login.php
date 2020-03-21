<?php

namespace App\Model\Security;

use Symfony\Component\Validator\Constraints as Assert;

class Login
{

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=32)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min=6, max=32)
     */
    private $password;

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}