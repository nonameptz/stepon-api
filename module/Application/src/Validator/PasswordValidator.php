<?php

namespace Application\Validator;

use Zend\Validator\Regex;

/**
 * Class PasswordValidator
 *
 * @package Application\Validator
 */
class PasswordValidator extends Regex
{
    /**
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID   => "Invalid type given. String, integer or float expected",
        self::ERROROUS  => "There was an internal error while using the pattern",
        self::NOT_MATCH => "This is an invalid password format.",
    ];

    /**
     * Sets validator options
     *
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        $pattern = '/^([a-zA-Z0-9@*#]{6,18})$/';
        is_array($options) ? $options['pattern'] = $pattern : $options = $pattern;
        parent::__construct($options);
    }
}
