<?php

namespace User\Filter;

use Zend\Crypt\Password\Bcrypt;
use Zend\Filter\AbstractFilter;

/**
 * Class PasswordFilter
 *
 * @package User\Filter
 */
class PasswordFilter extends AbstractFilter
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter($value)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(10);

        return $bcrypt->create($value);
    }
}
