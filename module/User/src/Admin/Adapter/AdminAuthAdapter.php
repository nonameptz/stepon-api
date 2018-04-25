<?php

namespace User\Admin\Adapter;

use User\Entity\User;
use Doctrine\ORM\EntityRepository;
use User\Enum\UserRole;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class AdminAuthAdapter
 *
 * @package User\Admin\Adapter
 */
class AdminAuthAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $password;

    /**
     * AuthAdapter constructor.
     *
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        if (empty($this->username) || empty($this->password)) {
            return new AuthenticationResult(
                AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Логин и пароль не могут быть пустые']
            );
        }

        /** @var User $user */
        $user = $this->repository->findOneBy(
            [
                'username' => $this->username,
                'role'     => UserRole::ADMIN,
            ]
        );

        if (!empty($user)) {
            if ($this->checkPassword($user->getPassword(), $this->password)) {
                return new AuthenticationResult(
                    AuthenticationResult::SUCCESS,
                    $user->getId(),
                    ['Authentication successful']
                );
            }

            return new AuthenticationResult(
                AuthenticationResult::FAILURE_CREDENTIAL_INVALID,
                null,
                ['Неправильный пароль']
            );
        }

        return new AuthenticationResult(
            AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND,
            null,
            [
                'Пользователь не существует',
            ]
        );
    }

    /**
     * @param string $credentials
     * @param string $password
     *
     * @return bool
     */
    private function checkPassword($credentials, $password)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(10);

        return $bcrypt->verify($password, $credentials);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
