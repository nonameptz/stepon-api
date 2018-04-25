<?php

namespace User\Admin\Form;

use Zend\Form\Form;

/**
 * Class AuthForm
 *
 * @package User\Admin\Form
 */
class AuthForm extends Form
{
    const ELEMENT_USERNAME = 'username';
    const ELEMENT_PASSWORD = 'password';

    public function __construct($name = null)
    {
        parent::__construct('authForm');

        $this->add(
            [
                'name'    => $this::ELEMENT_USERNAME,
                'type'    => 'Zend\Form\Element\Text',
                'options' => [
                    'label' => 'Логин',
                ],
            ]
        );
        $this->add(
            [
                'name'    => $this::ELEMENT_PASSWORD,
                'type'    => 'Zend\Form\Element\Password',
                'options' => [
                    'label' => 'Пароль',
                ],
            ]
        );
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'Submit',
                'attributes' => [
                    'value' => 'Войти',
                    'id'    => 'submitbutton',
                ],
            ]
        );
    }
}