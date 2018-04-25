<?php

namespace Application\Validator\Factory;

use Application\Util\UserIdentityTrait;
use Application\Validator\EmailExistValidator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Interop\Container\ContainerInterface;
use User\Entity\User;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class EmailExistValidatorFactory
 *
 * @package Application\Validator\Factory
 */
class EmailExistValidatorFactory implements FactoryInterface
{
    use UserIdentityTrait;

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EmailExistValidator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityRepository $repository */
        $repository = $container->get(EntityManager::class)->getRepository(User::class);

        $validator = new EmailExistValidator($repository);


        return $validator;
    }
}