<?php

namespace Application\Adapter;

use User\Driver\UserDriver;
use User\Enum\UserStatus;
use ZF\OAuth2\Adapter\PdoAdapter as OAuth2PdoAdapter;

/**
 * Class PdoAdapter
 *
 * @package Application\Adapter
 */
class PdoAdapter extends OAuth2PdoAdapter
{
    /**
     * @param string $username
     *
     * @return array|bool
     */
    public function getUser($username)
    {
        $user = parent::getUser($username);

        if (empty($user) || $user['status'] != UserStatus::ACTIVE) {
            return false;
        }

        return $user;
    }

    /**
     * @param string $access_token
     *
     * @return array|bool|mixed|null
     */
    public function getAccessToken($access_token)
    {
        $token = parent::getAccessToken($access_token);
        if (empty($token) || empty($token['user_id'])) {
            return false;
        }

        $user = $this->getUser($token['user_id']);
        if (empty($user)) {
            return false;
        }

        return $token;
    }
}
