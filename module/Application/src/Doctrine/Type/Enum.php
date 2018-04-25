<?php
namespace Application\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class Enum extends Type
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $values = array();

    /**
     * TODO make sense to retrieve this flag from entity definition
     * @var boolean
     */
    protected $nullable = true;

    /**
     * @param  array $fieldDeclaration
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(function($val) {
            return "'" . $val . "'";
        }, $this->values);

        return "ENUM(" . implode(", ", $values) . ") COMMENT '(DC2Type:" . $this->name . ")'";
    }

    /**
     * @param  mixed $value
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * @param  mixed $value
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (
            (null !== $value && !in_array($value, $this->values)) ||
            (null === $value && !$this->nullable)
        ) {
            throw new \InvalidArgumentException("Invalid '" . $this->name . "' value.");
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}