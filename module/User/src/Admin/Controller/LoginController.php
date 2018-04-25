<?php

namespace User\Admin\Controller;

use User\Admin\Adapter\AdminAuthAdapter;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZF\ContentNegotiation\Request;
use Zend\Authentication\AuthenticationService;
use User\Admin\Form\AuthForm;
use Zend\Authentication\Result;

/**
 * Class LoginController
 *
 * @package User\Admin\Controller
 */
class LoginController extends AbstractActionController
{
    /**
     * @var AuthenticationService
     */
    private $authService;

    /**
     * @var AuthForm
     */
    private $authForm;

    /**
     * LoginController constructor.
     *
     * @param AuthenticationService $authService
     * @param AuthForm              $authForm
     */
    public function __construct(AuthenticationService $authService, AuthForm $authForm)
    {
        $this->authService = $authService;
        $this->authForm    = $authForm;
    }

    /**
     * @return Response|ViewModel
     */
    public function loginAction()
    {
        if ($this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('admin');
        }

        /** @var Request $request */
        $request = $this->getRequest();
        $view    = new ViewModel(['form' => $this->authForm]);

        if (!$request->isPost()) {
            return $view;
        }

        $data = $request->getPost();
        $this->authForm->setData($data);

        if (!$this->authForm->isValid()) {
            $view->setVariable('messages', ['Логин и пароль не могут быть пустые']);

            return $view;
        }
        $authResult = $this->getAuthResult($this->authForm->getData());

        if (!$authResult->isValid()) {
            $view->setVariable('messages', ['Логин или пароль не совпадают']);

            return $view;
        }

        return $this->redirect()->toRoute('admin');
    }

    /**
     * @return Response|ViewModel
     */
    public function logoutAction()
    {
        if ($this->authService->hasIdentity()) {
            $this->authService->clearIdentity();
        }

        return $this->redirect()->toRoute('admin/login');
    }

    /**
     * @param array $data
     *
     * @return Result
     */
    private function getAuthResult($data)
    {
        /** @var AdminAuthAdapter $adapter */
        $adapter = $this->authService->getAdapter();
        $adapter->setUsername($data[AuthForm::ELEMENT_USERNAME]);
        $adapter->setPassword($data[AuthForm::ELEMENT_PASSWORD]);
        /** @var Result $authResult */
        $authResult = $this->authService->authenticate();

        return $authResult;
    }
}
