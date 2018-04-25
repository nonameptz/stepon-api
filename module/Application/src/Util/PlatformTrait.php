<?php

namespace Application\Util;

use Doctrine\DBAL\Migrations\AbstractMigration;

/**
 * Trait PlatformTrait
 *
 * @package Application\Util
 */
trait PlatformTrait
{

    /**
     * Aborts migration if inappropriate platform used
     *
     * @param string $name
     */
    public function guardForPlatform($name = 'mysql')
    {
        $doctrineMigrationClass = AbstractMigration::class;
        if (!$this instanceof $doctrineMigrationClass) {
            throw new \RuntimeException(
                sprintf('Trait %s could be used with %s implementations only', __TRAIT__, $doctrineMigrationClass)
            );
        }

        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != $name,
            sprintf('Current migration is allowed for %s platform only', $name)
        );
    }

} 