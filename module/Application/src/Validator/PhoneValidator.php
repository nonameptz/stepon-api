<?php

namespace Application\Validator;

use Zend\Validator\Regex;

/**
 * Class PhoneValidator
 *
 * @package Application\Validator
 */
class PhoneValidator extends Regex
{
    /**
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_MATCH => "This is an invalid phone format.",
    ];

    /**
     * Sets validator options
     *
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        $pattern = '/^7([0-9]{10})$/';
        is_array($options) ? $options['pattern'] = $pattern : $options = $pattern;
        parent::__construct($options);
    }
}
