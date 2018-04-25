<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RefreshToken
 *
 * @ORM\Table(name="oauth_refresh_tokens")
 * @ORM\Entity
 *
 * @package Application\Entity
 */
class RefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(name="refresh_token", type="string")
     *
     * @var string
     */
    private $refreshToken;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string")
     */
    private $username;

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return RefreshToken
     */
    public function setAccessToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return RefreshToken
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }
}