<?php

namespace Api\User\V1\Rpc\Login;

use Zend\Http\Response;
use Zend\Stdlib\ResponseInterface;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\OAuth2\Controller\AuthController;

/**
 * Class LoginController
 *
 * @package Api\User\V1\Rpc\Login
 *
 * @method array bodyParams() ZF\ContentNegotiation\ControllerPlugin\BodyParams plugin
 */
class LoginController extends AuthController
{
    /**
     * @return Response|ResponseInterface|ApiProblemResponse
     */
    public function loginAction()
    {
        return parent::tokenAction();
    }
}
