<?php

namespace User\Enum;

use Application\Doctrine\Type\Enum;

/**
 * Class UserRole
 *
 * @package User\Enum
 */
class UserRole extends Enum
{
    const CLIENT = 'client';
    const ADMIN  = 'admin';

    protected $name = 'enumUserRole';
    protected $values = [self::CLIENT, self::ADMIN];
}