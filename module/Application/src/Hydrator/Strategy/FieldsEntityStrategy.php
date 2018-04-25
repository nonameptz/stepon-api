<?php

namespace Application\Hydrator\Strategy;

use Doctrine\ORM\EntityManager;

/**
 * Class FieldsEntityStrategy
 *
 * @package Application\Hydrator\Strategy
 */
class FieldsEntityStrategy extends EntityStrategy
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * @param EntityManager $em
     * @param mixed         $className
     * @param array         $fields
     */
    public function __construct(EntityManager $em, $className, array $fields)
    {
        parent::__construct($em, $className);
        $this->fields = $fields;
    }

    /**
     * @param mixed $value
     * @param mixed $class
     *
     * @return array
     */
    protected function extractValue($value, $class)
    {
        if (empty($this->fields)) {
            return parent::extractValue($value, $class);
        }

        $result = [];
        foreach ($this->fields as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($value, $getter)) {
                $result[$field] = $value->$getter();
            }
        }

        return $result;
    }
}
