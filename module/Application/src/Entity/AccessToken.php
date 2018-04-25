<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AccessToken
 *
 * @ORM\Table(name="oauth_access_tokens")
 * @ORM\Entity
 *
 * @package Application\Entity
 */
class AccessToken
{
    /**
     * @ORM\Id
     * @ORM\Column(name="access_token", type="string")
     *
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string")
     */
    private $username;

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return AccessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
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
     * @return AccessToken
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}