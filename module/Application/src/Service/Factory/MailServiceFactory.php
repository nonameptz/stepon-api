<?php

namespace Application\Service\Factory;

use Application\Service\Config\PhpMailConfig;
use Application\Service\MailService;
use Interop\Container\ContainerInterface;
use RuntimeException;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;

/**
 * Class MailServiceFactory
 *
 * @package Application\Service\Factory
 */
class MailServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MailService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var RendererInterface $renderer */
        $renderer = $container->get(PhpRenderer::class);
        $config   = $this->getConfig($container);
        $service  = new MailService($renderer, $config);

        return $service;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return PhpMailConfig
     */
    public function getConfig(ContainerInterface $container)
    {
        /** @var array $config */
        $config = $container->get('config');
        if (empty($config['php_mail'])) {
            throw new RuntimeException('Config entry `php_mail` was not found');
        }

        if (empty($config['php_mail']['host'])) {
            throw new RuntimeException('Config entry `php_mail.host` was not found');
        }

        if (empty($config['php_mail']['username'])) {
            throw new RuntimeException('Config entry `php_mail.username` was not found');
        }

        if (empty($config['php_mail']['password'])) {
            throw new RuntimeException('Config entry `php_mail.password` was not found');
        }

        if (empty($config['php_mail']['privateStringDKIM'])) {
            throw new RuntimeException('Config entry `php_mail.privateStringDKIM` was not found');
        }

        if (empty($config['php_mail']['selectorDKIM'])) {
            throw new RuntimeException('Config entry `php_mail.selectorDKIM` was not found');
        }

        if (empty($config['php_mail']['domainDKIM'])) {
            throw new RuntimeException('Config entry `php_mail.domainDKIM` was not found');
        }

        return new PhpMailConfig($config['php_mail']);
    }
}
