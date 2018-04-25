<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        return $this->redirect()->toUrl('http://asd.ru/');
    }
}
