<?php

namespace Api\User\V1\Lib\Registration\InputFilter;

use Application\Util\SimpleInputFactoriesTrait;
use DoctrineModule\Validator\NoObjectExists;
use User\Entity\User;
use User\Filter\PasswordFilter;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;

/**
 * Class RegistrationInputFilter
 *
 * @package Api\User\V1\Lib\Registration\InputFilter
 */
class RegistrationInputFilter extends InputFilter
{
    use SimpleInputFactoriesTrait;

    const ELEMENT_USERNAME   = 'username';
    const ELEMENT_PASSWORD   = 'password';

    public function init()
    {
        $input = $this->addSimpleTextInput(self::ELEMENT_USERNAME, 255);
        $input->getValidatorChain()->attachByName(
            'NoObjectExists',
            [
                'object_repository' => User::class,
                'fields'            => ['username'],
                'messages'          => [
                    NoObjectExists::ERROR_OBJECT_FOUND => 'Provided email is existed',
                ],
            ]
        )->attachByName(EmailAddress::class);

        $input = $this->addSimpleTextInput(self::ELEMENT_PASSWORD, 2000);
        $input->getFilterChain()->attachByName(PasswordFilter::class);
    }
}