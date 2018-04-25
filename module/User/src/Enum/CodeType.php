<?php

namespace User\Enum;

use Application\Doctrine\Type\Enum;

/**
 * Class UserRole
 *
 * @package User\Enum
 */
class CodeType extends Enum
{
    const EMAIL = 'email';

    protected $name = 'enumCodeType';
    protected $values = [self::EMAIL];
}