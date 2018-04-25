<?php

namespace User\Admin\InputFilter;

use User\Admin\Form\AuthForm;
use Zend\InputFilter\InputFilter;

/**
 * Class AuthFormInputFilter
 *
 * @package User\Admin\InputFilter
 */
class AuthFormInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'     => AuthForm::ELEMENT_USERNAME,
                'required' => true,
            ]
        );
        $this->add(
            [
                'name'     => AuthForm::ELEMENT_PASSWORD,
                'required' => true,
            ]
        );
    }
}