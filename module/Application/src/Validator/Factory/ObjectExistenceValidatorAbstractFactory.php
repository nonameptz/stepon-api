<?php

namespace Application\Validator\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class ObjectExistenceValidatorAbstractFactory
 *
 * @package Application\Validator\Factory
 */
class ObjectExistenceValidatorAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Matching request names
     *
     * @var array
     */
    protected $matched = [
        'ObjectExists',
        'NoObjectExists',
        'DoctrineModule\\Validator\\ObjectExists',
        'DoctrineModule\\Validator\\NoObjectExists',
    ];

    /**
     * Available validators
     *
     * @var array
     */
    protected $available = [
        'objectexists'   => 'DoctrineModule\\Validator\\ObjectExists',
        'noobjectexists' => 'DoctrineModule\\Validator\\NoObjectExists',
    ];

    /**
     * Defined (after request) classes
     *
     * @var array
     */
    protected $defined = [];

    /**
     * @var array
     */
    protected $creationOptions = [];

    /**
     * Set creation options
     *
     * @param array $options
     *
     * @return void
     */
    public function setCreationOptions(array $options)
    {
        $this->creationOptions = $options;
    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (isset($this->matched[$requestedName])) {
            $this->defined[$requestedName] = $this->matched[$requestedName];
            return true;
        }

        if (!in_array($requestedName, $this->matched)) {
            return false;
        }

        $className = $requestedName;
        if (false !== strpos($requestedName, '\\')) {
            $fqcnParts = explode('\\', $requestedName);
            $className = array_pop($fqcnParts);
        }

        $className = strtolower($className);
        if (!isset($this->available[$className])) {
            return false;
        }

        $this->defined[$requestedName] = $this->available[$className];
        return true;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $className = $this->defined[$requestedName];

        $repositoryClass = isset($options['object_repository'])
            ? $options['object_repository']
            : null;

        if (isset($repositoryClass) && is_string($repositoryClass)) {
            /** @var EntityManager $entityManager */
            $entityManager = $container->get(EntityManager::class);

            $options['object_repository'] = $entityManager->getRepository($repositoryClass);
        }

        return new $className($options);
    }
}
