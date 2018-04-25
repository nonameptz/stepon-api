<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class SorryPageController
 *
 * @package Application\Controller
 */
class SorryPageController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
