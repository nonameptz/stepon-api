<?php

namespace User\Admin\Form\Factory;

use Interop\Container\ContainerInterface;
use User\Admin\Form\AuthForm;
use User\Admin\InputFilter\AuthFormInputFilter;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthFormFactory
 *
 * @package User\Admin\Form\Factory
 */
class AuthFormFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AuthForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authForm            = new AuthForm();
        $authFormInputFilter = $container->get('InputFilterManager')->get(AuthFormInputFilter::class);
        $authForm->setInputFilter($authFormInputFilter);

        return $authForm;
    }
}