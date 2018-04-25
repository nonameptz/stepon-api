<?php

namespace User\Enum;

use Application\Doctrine\Type\Enum;

/**
 * Class UserStatus
 *
 * @package User\Enum
 */
class UserStatus extends Enum
{
    const SUSPEND = 'suspend';
    const ACTIVE  = 'active';
    const REMOVED = 'removed';

    protected $name = 'enumUserStatus';
    protected $values = [self::ACTIVE, self::REMOVED, self::SUSPEND];
}