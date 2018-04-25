<?php

namespace Application\Admin\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

/**
 * Class AdminController
 *
 * @package Application\Admin\Controller
 */
class AdminController extends AbstractActionController
{
    /**
     * @var AuthenticationService
     */
    private $authService;

    /**
     * AdminController constructor.
     *
     * @param AuthenticationService $authService
     */
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('admin/login');
        }

        return new ViewModel();
    }
}
