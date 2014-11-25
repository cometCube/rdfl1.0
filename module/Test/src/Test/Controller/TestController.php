<?php

namespace Test\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Test\Model\Test;
use Test\Form\TestForm;
use Test\Model\LinkSettings;
use Test\Form\LinkSettingsForm;
use Zend\Session\Container;
use Zend\Json\Json;


class TestController extends AbstractActionController
{

    protected $testTable = null;

    protected $testQuestionsTable = null;

    protected $settingsTable = null;

    protected $linkTable = null;

    protected $linkSettingsTable = null;

    protected $linkAssignDatesTable = null;

    public function indexAction()
    {
        return new ViewModel ( array (
                				'tests' => $this->getTestTable ()->fetchAll () 
                		) );
    }

    public function addAction()
    {
        $form = new TestForm ();
                		$form->get ( 'submit' )->setValue ( 'Add Test' );
                		
                		$view = new ViewModel ();
                		$view->setTerminal ( $this->request->isXmlHttpRequest () );
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		
                		if ($request->isPost ()) {
                			$test = new Test (); // form data goes into this object.....
                			$form->setInputFilter ( $test->getInputFilter () ); // validating form data
                			$form->setData ( $request->getPost () ); // setting requested data to form object
                			if ($form->isValid ()) { // if validation is successful an form data is filled into album object
                				
                				$userSession = new Container ( 'users' );
                				$userid = $userSession->id;
                				$test->exchangeArray ( $form->getData () );
                				
                				$arr = $form->getData ();
                				$testname = $arr ['name'];
                				$chkId = $arr['id'];
                				
                				if ($this->checkval ( $chkId, $testname )) {
                					
                					$this->getTestTable ()->saveTest ( $test, $userid ); // saving into database
                					$response->setContent ( Json::encode ( array (
                							'status' => 0 
                					) ) );
                					return $response;
                				} else {
                					$response->setContent ( Json::encode ( array (
                							'status' => 2 
                					) ) );
                					return $response;
                				}
                				
                				// Redirect to list of albums
                				//return $this->redirect ()->toRoute ( 'test' ); // redirecting to album page
                			}
                			$response->setContent ( Json::encode ( array (
                					'status' => 1
                			) ) );
                			
                			$view->setVariables ( array (
                					'form' => $form
                			) );
                			return $view;
                		}
                		
                		$view->setVariables ( array (
                				'form' => $form 
                		) );
                		return $view; // if not data is posted then simply form is returned
    }

    public function editAction()
    {
        $request = $this->getRequest ();
                		if ($request->isPost ()) {
                			$id = $this->getRequest ()->getPost ( 'id', null );
                			$name = $this->getRequest ()->getPost ( 'name', null );
                		} else {
                			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 ); // getting id of newly entered album
                		}
                		
                		if (! $id) { // if id is not in the database then simply new album is added into database
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'add' 
                			) );
                		}
                		
                		// Get the Album with the specified id. An exception is thrown
                		// if it cannot be found, in which case go to the index page.
                		try {
                			$test = $this->getTestTable ()->getTest ( $id );
                		} catch ( \Exception $ex ) {
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'index' 
                			) );
                		}
                		
                		$form = new TestForm ();
                		$form->bind ( $test );
                		$form->get ( 'submit' )->setAttribute ( 'value', 'Edit' );
                		$view = new ViewModel ();
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		$response = $this->getResponse ();
                		
                		if ($request->isPost ()) {
                			
                			$form->setInputFilter ( $test->getInputFilter () );
                			$form->setData ( $request->getPost () );
                			
                			if ($form->isValid ()) {
                				$arr = ( array ) $form->getData ();
                				$chkId = $arr['id'];
                				$testname = $arr ['name'];
                				
                				$userSession = new Container ( 'users' );
                				$userid = $userSession->id;
                				
                				// $this->getTestTable ()->saveTest ( $test, $userid );
                				
                				if ($this->checkval ( $chkId, $testname )) {
                					
                					$this->getTestTable ()->saveTest ( $test, $userid ); // saving into database
                					$response->setContent ( Json::encode ( array (
                							'status' => 0 
                					) ) );
                					
                					return $response;
                				} else {
                					$response->setContent ( Json::encode ( array (
                							'status' => 1 
                					) ) );
                					return $response;
                				}
                			}
                			$response->setContent ( Json::encode ( array (
                					'status' => 2
                			) ) );
                			 
                			$view->setVariables ( array (
                					'form' => $form
                			) );
                			return $view;
                		}
                	
                		$view->setVariables ( array (
                				'id' => $id,
                				'form' => $form 
                		) );
                		return $view;
    }

    public function deleteAction()
    {
        $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
                		if (! $id) {
                			return $this->redirect ()->toRoute ( 'test' );
                		}
                		
                		$view = new ViewModel ();
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		
                		if ($request->isPost ()) {
                			$del = $request->getPost ( 'del', 'No' );
                			
                			if ($del == 'Confirm') {
                				$userSession = new Container ( 'users' );
                				$userid = $userSession->id;
                				$id = ( int ) $request->getPost ( 'id' );
                				$this->getTestTable ()->deleteTest ( $id,$userid);
                			}
                			
                			// Redirect to list of albums
                			return $this->redirect ()->toRoute ( 'test' );
                		}
                		
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		
                		$view->setVariables ( array (
                				'id' => $id,
                				'test' => $this->getTestTable ()->getTest ( $id ) 
                		) );
                		return $view;
    }

    public function viewTestAction()
    { 
        $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
                		if (! $id) {
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'index' 
                			) );
                		}
                		return new ViewModel ( array (
                				'testQuestions' => $this->getTestQuestions ()->fetchQuestions ( $id ) 
                		) );
    }

    public function testDetailsAction()
    {
        $id = ( int ) $this->params ()->fromRoute ( 'id', 0 ); // getting id of newly entered album
                		if (! $id) { // if id is not in the database then simply new album is added into database
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'index' 
                			) );
                		}
                		
                		// Get the Album with the specified id. An exception is thrown
                		// if it cannot be found, in which case go to the index page.
                		try {
                			$test = $this->getTestTable ()->getTest ( $id );
                		} catch ( \Exception $ex ) {
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'index' 
                			) );
                		}
                		
                		$view = new ViewModel ();
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		$view->setVariables ( array (
                				'testDetails' => $test 
                		) );
                		return $view;
    }

    public function testAddQuesAction()
    {
        $testId = ( int ) $this->params ()->fromRoute ( 'id', 0 );
                		
                		if (! $testId) {
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'index' 
                			) );
                		}
                		$listQuestions = $this->getTestQuestions ()->listQuestions ( $testId );
                		if (! empty ( $listQuestions )) {
                			return new ViewModel ( array (
                					'listQuestions' => $listQuestions,
                					'status' => 1 
                			) );
                		} else {
                			return new ViewModel ( array (
                					'listQuestions' => $listQuestions,
                					'status' => 0 
                			) );
    }
    }

    public function addSelectedQuesToTestAction()
    {
        $testId = ( int ) $this->params ()->fromRoute ( 'id', 0 );
                		
                		if (! $testId) {
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'index' 
                			) );
                		}
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		
                		$addQuesId = ( array ) $this->params ()->fromPost ( 'addQuesIds' );
                		
                		$result = $this->getTestQuestions ()->addSelectedQuesToTest ( $addQuesId );
                		
                		if ($result) {
                			$response->setContent ( JSON::encode ( array (
                					'flag' => 1 
                			) ) );
                			return $response;
                		}
                		$view = new ViewModel ();
                		$view->setTemplate ( '/test/viewTest/' );
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		return $view;
    }

    public function getTestTable()
    {
        if (! $this->testTable) {
                			$serviceManager = $this->getServiceLocator ();
                			$this->testTable = $serviceManager->get ( 'Test\Model\TestTable' );
                		}
                		return $this->testTable;
    }

    public function getTestQuestions()
    {
        if (! $this->testQuestionsTable) {
                			$serviceManager = $this->getServiceLocator ();
                			$this->testQuestionsTable = $serviceManager->get ( 'Test\Model\TestQuestionsTable' );
                		}
                		return $this->testQuestionsTable;
    }

    public function getLinkTable()
    {
        if (! $this->linkTable) {
                			$serviceManager = $this->getServiceLocator ();
                			$this->linkTable = $serviceManager->get ( 'Test\Model\LinkTable' );
                		}
                		return $this->linkTable;
    }

    public function getLinkSettingsTable()
    {
        if (! $this->linkSettingsTable) {
                    $serviceManager = $this->getServiceLocator ();
                    $this->linkSettingsTable = $serviceManager->get ( 'Test\Model\LinkSettingsTable' );
                }
                return $this->linkSettingsTable;
    }

    public function getLinkAssignDatesTable()
    {
        if (! $this->linkAssignDatesTable) {
                    $serviceManager = $this->getServiceLocator ();
                    $this->linkAssignDatesTable = $serviceManager->get ( 'Test\Model\LinkAssignDatesTable' );
                }
                return $this->linkAssignDatesTable;
    }

    public function generateTestLinkAction()
    {
        $testId = (int) $this->params()->fromRoute('id');            
				
                $form = new LinkSettingsForm();
                $request = $this->getRequest();  // getting current request object
				
                $testContent = $this->getTestQuestions()->testContent($testId);
                
                if ($request->isPost()) {
                    $linkSettings = new LinkSettings(); //form data goes into this object.....
                    $form->setInputFilter($linkSettings->getInputFilter());//validating form data
                    $form->setData($request->getPost());  // setting requested data to form object
                    if ($form->isValid()) {
                        $this->getLinkSettingsTable()->saveLinkSettings($form->getData());
                        return $this->redirect()->toRoute('test', array(
                                'action' => 'generateTestLink',
                                'id'     => $testId,

                            ));
                    }
                }

                //fetch test data
                $testData = $this->getTestTable()->getTest($testId);
                $linkAndTestData['testDetails'] = $testData->getArrayCopy();

                //fetch link data
                $linkData = $this->getLinkTable()->fetchLinkDetailsByTestId($testId);

                $i = 0;
                //fetch settings data
                if(!empty($linkData)) {
                    foreach ($linkData as $data) {
                        $data = $data->getArrayCopy();
                        $linkAndTestData['linkDetails'][$i] = $data;
                        $linkSettingsData = $this->getLinkSettingsTable()->fetchSettingsDetailsByLinkId($data['id']);
                        if(!empty($linkSettingsData)) {
                            $linkSettingsData = $linkSettingsData->getArrayCopy();
                            $linkAndTestData['linkSettingsDetails'][$i]= $linkSettingsData;
                        }
                        $i++;
                    }
                }

                $linkAndTestData['testContent'] = $testContent;
                $view=new ViewModel(array(
                    'linkAndTestData' => $linkAndTestData,
                    'form'=>$form
                ));
                $view->setTerminal($request->isXmlHttpRequest());
                return $view; //if not data is posted then simply frm is returned
    }

    public function mulDeleteTestAction()
    {
        $id = $this->params ()->fromQuery ( 'id' );
                		
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		if ($request->isPost ()) {
                			$del = $request->getPost ( 'del', 'No' );
                			
                			if ($del == 'Yes') {
                				$tid = $request->getPost ( 'tid' );
                				$testId = explode ( ",", $tid );
                				$userSession = new Container ( 'users' );
                				$userid = $userSession->id;
                				$this->getTestTable ()->mulDeleteTest ( $testId ,$userid);
                			}
                			
                			// Redirect to list of albums
                			return $this->redirect ()->toRoute ( 'test' );
                		}
                		
                		$view = new ViewModel ( array (
                				'testId' => $id 
                		) );
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		return $view;
    }

    public function mulDeleteTestAlertAction()
    {
        $viewmodel = new ViewModel();
            	$viewmodel -> setTerminal(true);
            	return $viewmodel;
    }

    public function assignTestAction()
    {
        $baseUrl = $this->getBaseUrl ();
                		$userSession = new Container ( 'users' );
                		$userId = $userSession->clientId;
                		$linkId = $this->params ()->fromRoute ( 'id' ); // linkId
                		                                                
                		// fetch link data
                		$linkData = $this->getLinkTable ()->getLink ( $linkId );
                		$arrData ['linkDetails'] = $linkData->getArrayCopy ();
                		$arrData ['linkDetails'] ['host'] = $userId; // client/user ID
                		
                		$i = 0;
                		$data = $this->getLinkAssignDatesTable ()->fetchLinkAssignDatesDetails ( $linkId );
                		foreach ( $data as $data ) {
                			$linkAssignDatesData = $data->getArrayCopy ();
                			$arrData ['recentLink'] [$i] = $linkAssignDatesData;
                			$arrData ['recentLink'] [$i] ['linkUrl'] = $this->getBaseUrl () . 'student/quiz/' . $userId . '/' . $linkId . '/' . $linkAssignDatesData ['link_code'];
                			$i ++;
                		}
                		$view = new ViewModel ( array (
                				'arrData' => $arrData 
                		) );
                		return $view;
    }

    public function generateLinkCodeAction()
    {
        $arrData ['baseUrl'] = $this->getBaseUrl ();
                $request = $this->getRequest ();
                $linkId = $request->getPost ( 'linkId' );
                $host = $request->getPost ( 'host' );
                $from = $request->getPost ( 'from' );
                $to = $request->getPost ( 'to' );
                $emailIds = $request->getPost ( 'emails' );
                		
                $code = $this->getCode ( 12 );
                		
                $arrData ['url'] = $this->getBaseUrl () . 'student/quiz/' . $host . '/' . $linkId . '/' . $code;
                $arrData ['emailIds'] = $emailIds;
                		
                $linkAssignDatesData ['linkId'] = $linkId;
                $linkAssignDatesData ['linkCode'] = $code;
                $linkAssignDatesData ['showFrom'] = $this->dateTimeFormat ( $from );
                $linkAssignDatesData ['showUntill'] = $this->dateTimeFormat ( $to );
                $linkAssignDatesData ['emails'] = $emailIds;
                		
                $isSaved = $this->getLinkAssignDatesTable ()->saveLinkAssignDatesAndEmails ( $linkAssignDatesData );
                		
                if (! $isSaved) {
                			die ( 'link assign dates could not be saved...' );
                }
                		
                $view = new ViewModel ( array (
                    'arrData' => $arrData 
                ) );
                $view->setTerminal ( true );
                return $view;
    }

    public function getCode($length = 6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
                		$code = substr ( str_shuffle ( $chars ), 0, $length );
                		return $code;
    }

    public function dateTimeFormat($date)
    {
        $newDate ="";
                if(isset($date) && !empty($date)) {
                    $date = explode(" ", $date);    
                    $meridian = $date[2];
                    $time = $date[1];
                    $date = $date[0];
                    $date = explode("/",$date);             
                    $mm = $date[0];
                    $dd = $date[1];
                    $yyyy = $date[2];               
                    $time = explode(":", $time);
                    $hr = $time[0];
                    $min = $time[1];
                    $sec = '00';
                    if(($meridian == 'PM' && $hr != '12')) {
                        $hr += 12;
                    }  
                    if($meridian == 'AM' && $hr == '12') {
                        $hr -= 12;
                    }                  
                    $newDate = $yyyy.'-'.$mm.'-'.$dd.' '.$hr.':'.$min.':'.$sec;
                }
                return $newDate;
    }

    public function getBaseUrl()
    {
        $sm = $this->getServiceLocator ();
        		$config = $sm->get ( 'config' );
        		return $config ['applicationSettings'] ['appLink'];
    }

    public function checkVal($chkId, $name)
    {
        $txtVal = $name;
        		
        		$checkVal = $this->getTestTable ()->checkVal ( $chkId, $txtVal );
                if ( $checkVal ) {
                	$response = $this->getresponse ();
                	return true;
                } else {
                	$response = $this->getResponse ();
                	return false;
    }
    }

    public function deleteTestQuesAction()
    {
        $quesid = ( int ) $this->params ()->fromRoute ( 'id', 0 );
                		if (! $quesid) {
                			return $this->redirect ()->toRoute ( 'test' );
                		}
                		
                		$view = new ViewModel ();
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		
                		if ($request->isPost ()) {
                			$del = $request->getPost ( 'del', 'No' );
                			
                			if ($del == 'Yes') {
                				$quesid = ( int ) $request->getPost ( 'id' );
                				$this->getTestQuestions ()->deleteTestQues ( $quesid );
                			}
                			$session = new \Zend\Session\Container ( 'test' );
                			$testId = $session->testId;
                			// Redirect to list of albums
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'viewTest',
                					'id' => $testId 
                			) );
                		}
                		
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		
                		$view->setVariables ( array (
                				'id' => $quesid 
                		// 'test' => $this->getTestTable ()->getTest ( $id )
                				) );
                		return $view;
    }

    public function muldeleteTestQuesAction()
    {
        $quesids = $this->params ()->fromQuery ( 'id', 0 );
                		$session = new \Zend\Session\Container ( 'test' );
                		$testId = $session->testId;
                		
                		$request = $this->getRequest ();
                		$response = $this->getResponse ();
                		if ($request->isPost ()) {
                			$del = $request->getPost ( 'del', 'No' );
                			
                			if ($del == 'Yes') {
                				$quesid = $request->getPost ( 'questionIds' );
                				$quesId = explode ( ",", $quesid );
                				$this->getTestQuestions ()->multideleteTestQues ( $quesId );
                			}
                			
                			// Redirect to list of albums
                			return $this->redirect ()->toRoute ( 'test', array (
                					'action' => 'viewTest',
                					'id' => $testId 
                			) );
                		}
                		
                		$view = new ViewModel ( array (
                				'quesIds' => $quesids 
                		) );
                		$view->setTerminal ( $request->isXmlHttpRequest () );
                		return $view;
                		
                		/*
                		 * $testId = ( int ) $this->params ()->fromRoute ( 'id', 0 ); if (! $testId) { return $this->redirect ()->toRoute ( 'test', array ( 'action' => 'index' ) ); } $request = $this->getRequest (); $response = $this->getResponse (); if ($request->isPost ()) { $del = $request->getPost ( 'del', 'No' ); if ($del == 'Yes') { $addQuesId = ( array ) $this->params ()->fromPost ( 'delQuesIds' ); $result = $this->getTestQuestions ()->addSelectedQuesToTest ( $addQuesId ); if ($result) { $response->setContent ( JSON::encode ( array ( 'flag' => 1 ) ) ); return $response; } } // Redirect to list of albums return $this->redirect ()->toRoute ( 'test' ); } $view = new ViewModel ( array ( 'testId' => $testId ) ); $view->setTerminal ( $request->isXmlHttpRequest () ); return $view;
                		 */
    }

    public function sendEmailsAction()
    {
        $request = $this->getRequest ();
            	$response = $this->getResponse ();
            	
            	$serviceManager = $this->getServiceLocator ();
            	$mailTemplate = $serviceManager->get('Email\Model\EmailTemplate');
            	$mailTemplateData = $mailTemplate->getMailTemplate('AssignLinkToStudent');
                
                $messageBody = $request->getPost ( 'url' );
                $emailIds = explode ( ',', $request->getPost ( 'emails' ) );
                
                $mailData['mailTemplateData'] = $mailTemplateData;
                $mailData['messageBody'] = $messageBody;
                $mailData['emailIds'] = $emailIds;
                
                $mailer = $serviceManager->get ( 'EmailService' );
                		
                if ($mailer->sendMail ( $mailData )) {
                    return $response->setContent ( Json::encode(array(
                        'status'=> 1 
                    )));

                } else {
                    return $response->setContent( Json::encode(array(
                        'status'=> 0 
                    )));
    }
    
    }

    public function deleteTestLinkAction()
    {
    	
        $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
        $session = new \Zend\Session\Container ( 'test' );
        $testId = $session->testId;
        if (! $id) {
                return $this->redirect ()->toRoute ( 'test',array('action'=>'generateTestLink','id'=>$testId) );
        }
      	        		
        $request = $this->getRequest ();
        $response = $this->getResponse ();
        $view = new ViewModel ();
        $view->setTerminal ( $request->isXmlHttpRequest () );
        
        if ($request->isPost ()) {
        	$del = $request->getPost ( 'del', 'No' );
                			
        	if ($del == 'Confirm') {
        		$userSession = new Container ( 'users' );
         		$userid = $userSession->id;
        		$id = ( int ) $request->getPost ( 'id' );
        		$this->getLinkTable()->deleteTestLink($id, $userid);
        	}
        	
            $this->redirect()->toUrl('/test/generateTestLink/'.$testId);   			
			//return $this->redirect ()->toRoute ( 'test', array('action' => 'viewTest','id'=>$testId,'param'=>$quesCount));
        }
        
        $view->setVariables ( array (
        					'id' => $id,
                			'link' => $this->getLinkTable()->getLink($id) 
        ) );
        return $view;
    }


}

