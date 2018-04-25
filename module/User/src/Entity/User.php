<?php

namespace User\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use User\Enum\UserRole;
use User\Enum\UserStatus;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 *
 * @package User\Entity
 */
class User
{
    use ArrayOrTraversableGuardTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=2000, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="enumUserStatus", nullable=true)
     */
    private $status = UserStatus::SUSPEND;

    /**
     * @var string
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="enumUserRole", nullable=false)
     */
    private $role = UserRole::CLIENT;

    /**
     * @var UserCode[]
     *
     * @ORM\OneToMany(
     *     targetEntity="User\Entity\UserCode",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     */
    private $codes;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->codes     = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return UserCode[]
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     * @param UserCode[] $codes
     *
     * @return User
     */
    public function setCodes($codes)
    {
        $this->codes->clear();
        if (empty($codes)) {
            return $this;
        }
        $this->guardForArrayOrTraversable($codes);

        foreach ($codes as $code) {
            $this->addCode($code);
        }

        return $this;
    }

    /**
     * @param UserCode $code
     *
     * @return User
     */
    public function addCode(UserCode $code)
    {
        foreach ($this->codes as $exist) {
            if ($exist->getType() == $code->getType()) {
                $exist->setCode($code->getCode());
                return $this;
            }
        }

        $this->codes->add($code);
        $code->setUser($this);

        return $this;
    }

    /**
     * @param UserCode $code
     *
     * @return User
     */
    public function removeCode(UserCode $code)
    {
        if ($this->codes->contains($code)) {
            $this->codes->removeElement($code);
        }

        return $this;
    }
}