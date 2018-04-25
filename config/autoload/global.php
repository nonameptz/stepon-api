<?php

return [
    'view_manager'    => [
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
    ],
    'zf-mvc-auth' => array(
        'authentication' => array(
            'map' => array(
                'Api' => 'oauth2_pdo',
            ),
        ),
    ),
    'storage'         => [
        'memcached.pool' => [
            'main' => [
                'host' => '127.0.0.1',
                'port' => '11211',
            ],
        ],
    ],
    'zf-oauth2'   => [
        'grant_types'     => [
            'password'           => true,
            'refresh_token'      => true,
            'client_credentials' => false,
            'authorization_code' => false,
            'jwt'                => false,
        ],
        'storage'         => 'ZF\OAuth2\Adapter\PdoAdapter',
        'access_lifetime' => 86400 * 7 * 100,
        'storage_settings' => array(
            'user_table' => 'users'
        ),
    ],
];
