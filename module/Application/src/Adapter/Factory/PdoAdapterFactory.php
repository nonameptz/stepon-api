<?php

namespace Application\Adapter\Factory;

use Application\Adapter\PdoAdapter;
use Interop\Container\ContainerInterface;
use ZF\OAuth2\Controller\Exception;

/**
 * Class PdoAdapterFactory
 *
 * @package Application\Adapter\Factory
 */
class PdoAdapterFactory
{
    /**
     * @param  ContainerInterface $container
     *
     * @return PdoAdapter
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        if (empty($config['zf-oauth2']['db'])) {
            throw new Exception\RuntimeException(
                'The database configuration [\'zf-oauth2\'][\'db\'] for OAuth2 is missing'
            );
        }

        $username = isset($config['zf-oauth2']['db']['username']) ? $config['zf-oauth2']['db']['username'] : null;
        $password = isset($config['zf-oauth2']['db']['password']) ? $config['zf-oauth2']['db']['password'] : null;
        $options  = isset($config['zf-oauth2']['db']['options']) ? $config['zf-oauth2']['db']['options'] : [];

        $oauth2ServerConfig = [];
        if (isset($config['zf-oauth2']['storage_settings'])
            && is_array($config['zf-oauth2']['storage_settings'])
        ) {
            $oauth2ServerConfig = $config['zf-oauth2']['storage_settings'];
        }

        return new PdoAdapter(
            [
                'dsn'      => $config['zf-oauth2']['db']['dsn'],
                'username' => $username,
                'password' => $password,
                'options'  => $options,
            ],
            $oauth2ServerConfig
        );
    }

    /**
     * Provided for backwards compatibility; proxies to __invoke().
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $container
     *
     * @return PdoAdapter
     */
    public function createService($container)
    {
        return $this($container);
    }
}
