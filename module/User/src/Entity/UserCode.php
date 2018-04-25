<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserCode
 *
 * @ORM\Table(name="user_code")
 * @ORM\Entity
 *
 * @package User\Entity
 */
class UserCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="enumCodeType", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=60, nullable=false)
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * UserCode constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return UserCode
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserCode
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return UserCode
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return UserCode
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return UserCode
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}