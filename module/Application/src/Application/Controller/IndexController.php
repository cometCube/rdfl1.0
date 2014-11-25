<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $ApplicationTable = null;

    public function indexAction()
    {
    	$vr = $this->params()->fromQuery('id');
    	$vr1 = $this->params()->fromQuery('statusmsg');
    
        return new ViewModel(array('id'=>$vr,'statusmsg'=>$vr1));
    }
}

