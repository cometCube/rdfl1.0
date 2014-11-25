<?php

namespace Result\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Json\Json;

class ResultController extends AbstractActionController
{
	protected $resultTable;

    public function indexAction()
    {
    	$testname=$this->params()->fromQuery('testname');
    	if(isset($testname)){
    		return new ViewModel(array(
    				'results' => $this->getResultTable()->fetch_result(),
    				'tests' => $this->getResultTable()->fetch_tests_by_testid($testname),
    				'testname'=>$testname
    		));
    	}
        return new ViewModel(array(
            'results' => $this->getResultTable()->fetch_result(),
        	'tests' => $this->getResultTable()->fetch_tests(),
        ));
    }
    public function getResultTable()
    {
        if (!$this->resultTable) {
			
			$serviceManager   = $this->getServiceLocator();
            
            $this->resultTable = $serviceManager->get('Result\Model\ResultTable');

        }
        return $this->resultTable;
    }
    
    public function deleteAction()
    {
    	$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
    	if (! $id) {
    		return $this->redirect ()->toRoute ( 'result' );
    	}
    	
    	
    	$view = new ViewModel ();
    	$request = $this->getRequest ();
    	$response = $this->getResponse ();
    	
    	if ($request->isPost ()) {
    		$response = $this->getResponse ();
    		$del = $request->getPost ( 'del', 'No' );
    			$userSession = new Container ( 'users' );
    			$this->getResultTable()->deleteResult ( $id);
    		 
    		// Redirect to list of albums
    		$response->setContent ( Json::encode ( array (
							'status' => 1 
				)));
					return $response;
    	}
    
    	$view->setTerminal ( $request->isXmlHttpRequest () );
    
    	$view->setVariables ( array (
    			'id' => $id,
    			'result' => $this->getResultTable ()->get_result ($id)
    ));
    	return $view;
    }
    
    public function deleteallAction() {
    	$request = $this->getRequest ();
    	
    	
    	if ($request->isPost ()) {
    		$response = $this->getResponse ();
	    	$id = $this->params ()->fromPost('id');
	    	
	    	$this->getResultTable ()->deleteallResult ( $id);
	    	 
	    		$response->setContent ( Json::encode ( array (
							'status' => 1 
					) ) );
					return $response;
	    	}
    	
    	
    	$view = new ViewModel ();
    	$view->setTerminal ( true );
    	return $view;
    }
    
    
}

