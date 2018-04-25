<?php

namespace Application\Util;

use RuntimeException;
use Zend\Filter\Boolean;
use Zend\Filter\ToNull as NullFilter;
use Zend\I18n\Validator\IsInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\NotEmpty;
use DoctrineModule\Validator\ObjectExists;

/**
 * Trait SimpleInputFactoriesTrait
 *
 * @package Application\Util
 */
trait SimpleInputFactoriesTrait
{
    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $castToNull
     * @param string $type
     *
     * @return Input
     */
    public function addSimpleInput($name, $required = true, $castToNull = true, $type = null)
    {
        $this->checkInstance();

        $spec = [
            'name'     => $name,
            'required' => $required,
        ];
        if ($type !== null) {
            $spec['type'] = $type;
        }

        $this->add($spec);

        $input = $this->get($name);
        if (!$input instanceof Input) {
            throw new RuntimeException('Input must be instance of ' . Input::class);
        }

        if (false === $required && true === $castToNull) {
            $input->getFilterChain()->attachByName(NullFilter::class);
        }

        return $input;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param int    $length
     * @param bool   $castToNull
     *
     * @return Input
     */
    public function addSimpleTextInput($name, $length = null, $required = true, $castToNull = true)
    {
        $input = $this->addSimpleInput($name, $required, $castToNull);
        $input->getFilterChain()
              ->attachByName('StripTags')
              ->attachByName('StringTrim');
        if (isset($length) && ($length = intval($length)) > 0) {
            $input->getValidatorChain()->attachByName('StringLength', ['max' => $length], true);
        }

        return $input;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $castToNull
     *
     * @return Input
     */
    public function addSimpleIntegerInput($name, $required = true, $castToNull = true)
    {
        $input = $this->addSimpleInput($name, $required, $castToNull);
        $input->getValidatorChain()->attachByName(
            IsInt::class,
            ['messages' => [IsInt::NOT_INT => 'Only a whole number may be used here']],
            true
        );

        return $input;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $castToNull
     *
     * @return Input
     */
    public function addSimpleNumberInput($name, $required = true, $castToNull = true)
    {
        $input = $this->addSimpleInput($name, $required, $castToNull);
        //$input->getFilterChain()->attachByName('Number', ['precision' => 2]);
        $input->getValidatorChain()
              ->attachByName(
                  'Between',
                  [
                      'min'     => 0.01, 'max' => 10000000 - 0.01,
                      'message' => 'Only numbers are allowed',
                  ]
              );
        return $input;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $castToNull
     *
     * @return Input
     */
    public function addSimpleBooleanInput($name, $required = true, $castToNull = true)
    {
        $input = $this->addSimpleInput($name, $required, $castToNull);
        $input->getFilterChain()
              ->attachByName(
                  Boolean::class,
                  [
                      'type'    => [Boolean::TYPE_ALL ^ Boolean::TYPE_NULL],
                      'casting' => false,
                  ]
              );
        if ($required === true) {
            $input->getValidatorChain()
                  ->prependByName(
                      NotEmpty::class,
                      [
                          'type' => [NotEmpty::ALL ^ NotEmpty::BOOLEAN],
                      ],
                      true
                  );
        }

        return $input;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $castToNull
     *
     * @return Input
     */
    public function addSimpleFileInput($name, $required = true, $castToNull = true)
    {
        $input = $this->addSimpleInput($name, $required, $castToNull, FileInput::class);
        return $input;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $castToNull
     *
     * @return Input
     */
    public function addSimpleListInput($name, $required = true, $castToNull = true)
    {
        $input = $this->addSimpleInput($name, $required, $castToNull);
        $input->getFilterChain()
              ->attachByName(
                  'app.filter.collectionFilterChain',
                  [
                      'filters' => [
                          ['name' => 'StripTags'],
                          ['name' => 'StringTrim'],
                      ],
                  ]
              );
        $input->getValidatorChain()
              ->attachByName('app.validator.uniqueList', ['item_name' => $name], true);

        return $input;
    }

    /**
     * @param Input  $input
     * @param string $class
     * @param string $field
     * @param string $name
     *
     * @return Input
     */
    protected function addObjectExistValidator(Input $input, $class, $field, $name)
    {
        $input->getValidatorChain()
              ->attachByName('NotEmpty', ['type' => [NotEmpty::ALL]], true)
              ->attachByName(
                  'ObjectExists',
                  [
                      'object_repository' => $class,
                      'fields'            => [$field],
                      'messages'          => [
                          ObjectExists::ERROR_NO_OBJECT_FOUND => sprintf('Provided %s is not found', $name),
                      ],
                  ]
              );

        return $input;
    }

    /**
     * Insures Instance where trait was called is type of InputFilterInterface
     */
    private function checkInstance()
    {
        if (!$this instanceof InputFilterInterface) {
            throw new RuntimeException(
                sprintf('This trait can only be used with %s instances', InputFilterInterface::class)
            );
        }
    }
}
