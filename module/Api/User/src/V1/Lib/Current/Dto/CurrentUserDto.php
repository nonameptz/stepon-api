<?php

namespace Api\User\V1\Lib\Current\Dto;

/**
 * Class CurrentUserDto
 *
 * @package Api\User\V1\Lib\Current\Dto
 */
class CurrentUserDto
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $role;

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
     * @return CurrentUserDto
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return CurrentUserDto
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
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
     * @return CurrentUserDto
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}