<?php

namespace Api\User\V1\Lib\Current\InputFilter;

use Application\Util\SimpleInputFactoriesTrait;
use User\Filter\PasswordFilter;
use Zend\InputFilter\InputFilter;

/**
 * Class CurrentUserInputFilter
 *
 * @package Api\User\V1\Lib\Current\InputFilter
 */
class CurrentUserInputFilter extends InputFilter
{
    use SimpleInputFactoriesTrait;

    const ELEMENT_PASSWORD = 'password';

    public function init()
    {
        $input = $this->addSimpleTextInput(self::ELEMENT_PASSWORD, 2000, false);
        $input->getFilterChain()->attachByName(PasswordFilter::class);
    }

    /**
     * @param array $inputs
     * @param array $data
     * @param null  $context
     *
     * @return bool
     */
    protected function validateInputs(array $inputs, $data = [], $context = null)
    {
        if (empty($this->data[self::ELEMENT_PASSWORD])) {
            $this->remove(self::ELEMENT_PASSWORD);
        }

        return parent::validateInputs($inputs, $data, $context);
    }
}