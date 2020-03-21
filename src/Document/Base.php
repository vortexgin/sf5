<?php

namespace App\Document;

use App\Constraints\DateTime;
use App\Constraints\EntityExists;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class BaseDocument
 * @package App\Document
 * @MongoDB\MappedSuperclass
 * @MongoDB\HasLifecycleCallbacks
 */
class Base
{

    /**
     * Registering validator constraint
     * @param ClassMetadata $metadata
     * @return ClassMetadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('createdAt', new DateTime());
        $metadata->addConstraint(new EntityExists([
            'fields' => 'createdBy',
            'entityClass' => User::class
        ]));

        $metadata->addPropertyConstraint('updatedAt', new DateTime());
        $metadata->addConstraint(new EntityExists([
            'fields' => 'updatedBy',
            'entityClass' => User::class
        ]));

        return $metadata;
    }

    /**
     * @var string
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var bool
     * @MongoDB\Field(type="boolean", nullable=false)
     */
    protected $isActive = true;

    /**
     * @var \DateTime
     * @MongoDB\Field(type="date", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \App\Document\User
     * @MongoDB\ReferenceOne(targetDocument=\App\Document\User::class, nullable=false, cascade={"detach"})
     */
    protected $createdBy;

    /**
     * @var \DateTime
     * @MongoDB\Field(type="date", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \App\Document\User
     * @MongoDB\ReferenceOne(targetDocument=\App\Document\User::class, nullable=true, cascade={"detach"})
     */
    protected $updatedBy;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return Base
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Base
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return User
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     * @return Base
     */
    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Base
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @MongoDB\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return User
     */
    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    /**
     * @param User $updatedBy
     * @return Base
     */
    public function setUpdatedBy(User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}