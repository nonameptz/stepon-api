<?php

namespace Application\Validator;

use Doctrine\ORM\EntityRepository;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

/**
 * Class EmailExistValidator
 *
 * @package Application\Validator
 */
class EmailExistValidator extends AbstractValidator
{
    const EXIST = 'exist';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::EXIST => "Email is existed.",
    ];

    private $userEmail = null;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * Sets validator options
     *
     * {@inheritdoc}
     */
    public function __construct(EntityRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     *
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $user = $this->repository->findBy(['email' => $value]);

        if (!empty($user) && !empty($this->userEmail) && $this->userEmail != $value) {
            $this->error(self::EXIST);
            return false;
        }

        return true;
    }

    /**
     * @return null
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param null $userEmail
     *
     * @return EmailExistValidator
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
        return $this;
    }
}
