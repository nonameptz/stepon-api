<?php

namespace Application\Storage\Factory;

use Interop\Container\ContainerInterface;
use Memcached;

/**
 * Class MemcachedPoolFactory
 */
class MemcachedPoolFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('Config');
        $pool   = isset($config['storage']['memcached.pool']) ? $config['storage']['memcached.pool'] : null;

        if (!is_array($pool)) {
            throw new \RuntimeException(sprintf('storage.memcached.pool is not defined'));
        }

        $memcached = new Memcached();

        foreach ($pool as $name => $spec) {
            $memcached->addServer($this->extractHost($spec, $name), $this->extractPort($spec, $name));
        }

        return $memcached;
    }

    protected function extractHost($spec, $name)
    {
        if (!is_array($spec)) {
            throw new \RuntimeException(
                sprintf('Specification for [%s] MUST be an array', $name)
            );
        }

        if (!isset($spec['host'])) {
            throw new \RuntimeException(
                sprintf('Specification for [%s] MUST specify the `host` key', $name)
            );
        }

        return $spec['host'];
    }

    protected function extractPort($spec, $name)
    {
        if (!is_array($spec)) {
            throw new \RuntimeException(
                sprintf('Specification for [%s] MUST be an array', $name)
            );
        }

        if (!isset($spec['port'])) {
            throw new \RuntimeException(
                sprintf('Specification for [%s] MUST specify the `port` key', $name)
            );
        }

        return $spec['port'];
    }
}
