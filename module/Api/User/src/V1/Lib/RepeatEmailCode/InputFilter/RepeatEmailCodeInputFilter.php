<?php

namespace Api\User\V1\Lib\RepeatEmailCode\InputFilter;

use Application\Util\SimpleInputFactoriesTrait;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;

/**
 * Class RepeatEmailCodeInputFilter
 *
 * @package Api\User\V1\Lib\RepeatEmailCode\InputFilter
 */
class RepeatEmailCodeInputFilter extends InputFilter
{
    use SimpleInputFactoriesTrait;

    const ELEMENT_EMAIL = 'email';

    public function init()
    {
        $input = $this->addSimpleTextInput(self::ELEMENT_EMAIL, 50);
        $input->getValidatorChain()->attachByName(EmailAddress::class);
    }
}