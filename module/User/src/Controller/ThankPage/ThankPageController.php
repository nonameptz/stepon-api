<?php

namespace User\Controller\ThankPage;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ThankPageController
 *
 * @package User\Controller\ThankPage
 */
class ThankPageController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function thankAction()
    {
        return new ViewModel();
    }
}
