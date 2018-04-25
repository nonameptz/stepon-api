<?php

namespace User\Model;

use Application\Driver\AccessTokenDriver;
use Application\Driver\RefreshTokenDriver;
use Application\Util\Criterion\Filter;
use User\Criterion\UserCodeCriteria;
use User\Criterion\UserNameCriteria;
use User\Driver\UserDriver;
use User\Entity\User;
use User\Entity\UserCode;
use User\Enum\CodeType;
use User\Enum\UserStatus;
use Zend\Router\RouteStackInterface;

/**
 * Class UserModel
 *
 * @package User\Model
 */
class UserModel
{
    /**
     * @var UserDriver
     */
    private $driver;

    /**
     * @var AccessTokenDriver
     */
    private $accessTokenDriver;

    /**
     * @var RefreshTokenDriver
     */
    private $refreshTokenDriver;

    /**
     * @var RouteStackInterface
     */
    private $router;

    /**
     * @var string
     */
    private $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * UserModel constructor.
     *
     * @param UserDriver          $driver
     * @param AccessTokenDriver   $accessTokenDriver
     * @param RefreshTokenDriver  $refreshTokenDriver
     * @param RouteStackInterface $router
     */
    public function __construct(
        UserDriver $driver,
        AccessTokenDriver $accessTokenDriver,
        RefreshTokenDriver $refreshTokenDriver,
        RouteStackInterface $router
    ) {
        $this->driver             = $driver;
        $this->accessTokenDriver  = $accessTokenDriver;
        $this->refreshTokenDriver = $refreshTokenDriver;
        $this->router             = $router;
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function fetch($id)
    {
        return $this->driver->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return User[]
     */
    public function findByFilter(Filter $filter)
    {
        return $this->driver->findByFilter($filter);
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function save(User $user)
    {
        return $this->driver->save($user);
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->driver->delete($user);
    }

    /**
     * @param User $user
     */
    public function clearTokens(User $user)
    {
        $this->save($user);
        $this->accessTokenDriver->clear($user);
        $this->refreshTokenDriver->clear($user);
    }

    /**
     * @param User $user
     * @param null $code
     *
     * @return string
     */
    public function createConfirmLink(User $user, $code = null)
    {
        if (empty($code)) {
            $code = "";

            $characters = preg_split('//', $this->characters);

            for ($i = 0; $i < 15; $i++) {
                $code .= $characters[random_int(0, count($characters) - 1)];
            }
        }

        $userCode = new UserCode();
        $userCode->setType(CodeType::EMAIL)->setCode($code);
        $user->addCode($userCode);
        $this->save($user);

        $link = $this->router->assemble(
            [],
            [
                'name'            => 'user/confirm-email',
                'force_canonical' => true,
                'query'           => ['code' => $code, 'email' => urlencode($user->getUsername())],
            ]
        );

        return $link;
    }

    /**
     * @param string $code
     * @param string $email
     *
     * @return null|User
     */
    public function activateUser($code, $email)
    {
        $filter = new Filter();
        $filter->attachCriteria('code', new UserCodeCriteria());
        $filter->attachCriteria('username', new UserNameCriteria());
        $filter->populate(
            [
                'code'  => $code,
                'username' => $email,
            ]
        );
        $users = $this->findByFilter($filter);
        if (empty($users)) {
            return null;
        }

        $user = array_shift($users);
        foreach ($user->getCodes() as $code) {
            if ($code->getType() == CodeType::EMAIL) {
                $user->removeCode($code);
            }
        }

        $user->setStatus(UserStatus::ACTIVE);
        $this->save($user);

        return $user;
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return User
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $user = $this->driver->findOneBy($criteria, $orderBy);

        return $user;
    }
}