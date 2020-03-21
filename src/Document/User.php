<?php

namespace App\Document;

use App\Document\Base as BaseDocument;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @MongoDB\Document(repositoryClass=UserRepository::class)
 * @MongoDB\HasLifecycleCallbacks
 */
class User extends BaseDocument implements UserInterface
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    const EDUCATION_JUNIOR_SCHOOL = 'Junior School Degree';
    const EDUCATION_HIGH_SCHOOL = 'High School Degree';
    const EDUCATION_DIPLOMA = 'Diploma Degree';
    const EDUCATION_BACHELOR = 'Bachelor Degree';
    const EDUCATION_MASTER = 'Master Degree';

    const WORK_STUDENT = 'Student';
    const WORK_EMPLOYEE = 'Employee';
    const WORK_GOV_EMPLOYEE = 'Goverment Employee';
    const WORK_ENTERPREUNER = 'Enterpreuner';

    public static $fields = ['id', 'name', 'email', 'roles', 'active', 'createdAt'];

    /**
     * {@inheritDoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata = parent::loadValidatorMetadata($metadata);

        $metadata->addConstraint(new UniqueEntity([
            'service' => UniqueEntityValidator::class,
            'fields' => 'email',
        ]));

        $metadata->addPropertyConstraint('role', new Assert\NotBlank());
        $metadata->addPropertyConstraints('email', [
            new Assert\Email(), new Assert\NotBlank(), new Assert\Length(['max' => 32])
        ]);
        $metadata->addPropertyConstraints('password', [
            new Assert\NotBlank(), new Assert\Length(['min' => 6])
        ]);
        $metadata->addPropertyConstraint('point', new Assert\PositiveOrZero());
    }

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=false)
     */
    private $role = self::ROLE_USER;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=false)
     * @MongoDB\Index(unique=true, order="asc", background=true)
     */
    private $email;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=false)
     */
    private $password;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     * @MongoDB\Index(order="asc", background=true)
     */
    private $mobilePhone;

    /**
     * @var \DateTime
     * @MongoDB\Field(type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     */
    private $education;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     */
    private $work;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=false)
     * @MongoDB\Index(order="asc", background=true)
     */
    private $confirmationCode;

    /**
     * @var bool
     * @MongoDB\Field(type="boolean", nullable=false)
     */
    private $enabled = false;

    /**
     * @var int
     * @MongoDB\Field(type="int", nullable=false)
     * @MongoDB\Index(order="asc", background=true)
     */
    private $point = 0;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     * @MongoDB\Index(order="asc", background=true)
     */
    private $resetCode;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     */
    private $emailCanonical;

    /**
     * @var string
     * @MongoDB\Field(type="string", nullable=true)
     */
    private $mobilePhoneCanonical;

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getId();
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [$this->getRole()];
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return User
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }


    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
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
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPlainPassword(string $password, $salt = null): self
    {
        $this->password = hash_hmac('sha256', $password, $salt);

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    /**
     * @param string $mobilePhone
     * @return User
     */
    public function setMobilePhone(string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     * @return User
     */
    public function setBirthDate(\DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getEducation(): ?string
    {
        return $this->education;
    }

    /**
     * @param string $education
     * @return User
     */
    public function setEducation(string $education): self
    {
        if (in_array($education, [
            self::EDUCATION_JUNIOR_SCHOOL, self::EDUCATION_HIGH_SCHOOL, self::EDUCATION_DIPLOMA,
            self::EDUCATION_BACHELOR, self::EDUCATION_MASTER,
        ])) {
            $this->education = $education;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getWork(): ?string
    {
        return $this->work;
    }

    /**
     * @param string $work
     * @return User
     */
    public function setWork(string $work): self
    {
        if (in_array($work, [
            self::WORK_STUDENT, self::WORK_EMPLOYEE, self::WORK_GOV_EMPLOYEE,
            self::WORK_ENTERPREUNER,
        ])) {
            $this->work = $work;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->getConfirmationCode();
    }

    /**
     * @return string
     */
    public function getConfirmationCode(): ?string
    {
        return $this->confirmationCode;
    }

    /**
     * @param string $confirmationCode
     * @return User
     */
    public function setConfirmationCode(string $confirmationCode): self
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function setConfirmationCodeValue()
    {
        $this->confirmationCode = sha1(sprintf('%s+%s', $this->email, random_bytes(128)));
    }

    /**
     * @return bool
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return int
     */
    public function getPoint(): ?int
    {
        return $this->point;
    }

    /**
     * @param int $point
     * @return User
     */
    public function setPoint(int $point): self
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return string
     */
    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    /**
     * @param string $resetCode
     * @return User
     */
    public function setResetCode(string $resetCode): self
    {
        $this->resetCode = $resetCode;

        return $this;
    }

    public function setResetCodeValue()
    {
        $this->confirmationCode = random_bytes(128);
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    /**
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical(string $emailCanonical): self
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobilePhoneCanonical(): ?string
    {
        return $this->mobilePhoneCanonical;
    }

    /**
     * @param string $mobilePhoneCanonical
     * @return User
     */
    public function setMobilePhoneCanonical(string $mobilePhoneCanonical): self
    {
        $this->mobilePhoneCanonical = $mobilePhoneCanonical;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}