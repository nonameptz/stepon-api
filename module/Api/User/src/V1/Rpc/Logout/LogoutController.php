<?php

namespace Api\User\V1\Rpc\Logout;

use Application\Util\ProblemResponseTrait;
use Application\Util\UserIdentityTrait;
use User\Model\UserModel;
use Util\Logger\PPLog;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class LogoutController
 *
 * @package Api\User\V1\Rpc\Logout
 */
class LogoutController extends AbstractActionController
{
    use UserIdentityTrait, ProblemResponseTrait;

    /**
     * @var UserModel
     */
    private $model;

    /**
     * Constructor
     *
     * @param UserModel $model
     */
    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return Response|ApiProblemResponse
     */
    public function logoutAction()
    {
        try {
            $user = $this->extractUserFromEvent($this->getEvent());
            $this->model->clearTokens($user);

            /** @var Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_204);

            return $response;
        } catch (\Exception $ex) {
            PPLog::exception($ex);

            return $this->problemResponse(Response::STATUS_CODE_500, 'Error occurred during logout');
        }
    }
}
