<?php

namespace Certificate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AssetManager\Cache\FilePathCache;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\Mime\Mime as Mime;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use DOMPDFModule\View\Model\PdfModel;
use Zend\View\Model\JsonModel;

use Certificate\Model\Certificate;
use Certificate\Form\CertificateForm;

class CertificateController extends AbstractActionController
{

    protected $certificateTable = null;

    protected $studentTable = null;

    protected $testTable = null;

    protected $linkTable = null;

    protected $docRoot = null;

    public function indexAction()
    {
        return new ViewModel ( array (
                                				'certificates' => $this->getCertificateTable ()->fetchAll () 
                                		) );
    }

    public function createNewCertificateAction()
    {
        $tests = $this->getTestTable ()->getTests();
                
                $assignedToData = $this->getCertificateTable()->getAssignedTo ( $tests[0]['id'] );
                
                $arrData['tests'] = $tests;
                              
                $arrData['assignedTo']  = $assignedToData;	
                                		
                                		$certificateform = new CertificateForm ( $arrData );
                                		
                                		$request = $this->getRequest ();
                                		$response = $this->getResponse ();
                                		
                                		if ($request->isPost ()) {
                                			$certificate = new Certificate ();
                                			$certificateform->setData ( $request->getPost () );
                                			if ($certificateform->isValid ()) {
                                				$userSession = new Container ( 'users' );
                                				$userid = $userSession->id;
                                				$certificate->exchangeArray ( $certificateform->getData () );
                                				
                                				$lastInsertId = $this->getCertificateTable ()->saveCertificate ( $certificate, $userid ); // saving into database
                                				$response->setContent ( Json::encode ( array (
                                						'status' => 1,
                                						'lastInsertId' => $lastInsertId 
                                                ) ) );
                                                return $response;
                                			}
                                		}
                                		
                                		$viewmodel = new ViewModel ( array (
                                				'form' => $certificateform,
                                				'assignedToData' => $assignedToData,
                                		) );
                                		$viewmodel->setTerminal ( $request->isXmlHttpRequest () );
                                		return $viewmodel;
    }

    public function getCertificateTable()
    {
        if (! $this->certificateTable) {
                                			$sm = $this->getServiceLocator ();
                                			$this->certificateTable = $sm->get ( 'Certificate\Model\CertificateTable' );
                                		}
                                		return $this->certificateTable;
    }

    public function generateCertificateAction()
    {
		$id = $this->params ()->fromRoute ( 'id' );
		
		$title = $this->getRequest ()->getPost ( 'titlepre' );
		$assignedTo = $this->getRequest ()->getPost ( 'assigned_topre' );
		$certiImage = $this->getRequest ()->getPost ( 'certiImage' );
		$percentage = $this->getRequest ()->getPost ( 'percenatge_pre' );
		$date = $this->getRequest ()->getPost ( 'date_pre' );
		$fileName = $this->getRequest ()->getPost ( 'fileName' );

		$pdf = new PdfModel ();
		$pdf->setOption ( "paperSize", "a4" ); // Defaults to 8x11
		$pdf->setOption ( "paperOrientation", "landscape" ); // Defaults to portrait
		                                                     
		// if fileName does not exists then only preview else view, save and email
		if (isset ( $fileName ) && ! empty ( $fileName )) {
			
			$pdf->setOption ( "savePath", $this->getDocRoot () . "/data/cache/" );
			$pdf->setOption ( "fileName", $fileName );
		}
		
		$pdf->setVariables ( array (
				'title' => $title,
				'name' => $assignedTo,
				'certiImage' => $certiImage,
				'percentage' => $percentage,
				'date' => $date 
		) );
		
		return $pdf;
    }

    public function sendEmailAction()
    {
        $response = $this->getResponse ();
                                		$emailId [] = $this->params ()->fromPost ( 'emailId' );
                                		$fileName = $this->params ()->fromPost ( 'fileName' );
                                		
                                		$serviceManager = $this->getServiceLocator ();
                                		$mailTemplate = $serviceManager->get ( 'Email\Model\EmailTemplate' );
                                		$mailTemplateData = $mailTemplate->getMailTemplate ( 'sendcertificatetostudent' );
                                		
                                		// create a MimeMessage object that will hold the mail body and any attachments
                                		$bodyPart = new MimeMessage ();
                                		$file = file_get_contents ( $this->getDocRoot () . '/data/cache/' . $fileName );
                                		$attachment = new MimePart ( $file );
                                		$attachment->type = 'application/pdf';
                                		$attachment->filename = $fileName;
                                		$attachment->encoding = Mime::ENCODING_BASE64;
                                		$attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
                                		
                                		// create the mime part for the message body
                                		// you can add one for text and one for html if needed
                                		$bodyMessage = new MimePart ( "Please Find Your Certificate In Attachment" );
                                		$bodyMessage->type = 'text/html';
                                		
                                		// add the message body and attachment(s) to the MimeMessage
                                		$bodyPart->setParts ( array (
                                				$bodyMessage,
                                				$attachment 
                                		) );
                                		
                                		$mailData ['mailTemplateData'] = $mailTemplateData;
                                		$mailData ['messageBody'] = $bodyPart;
                                		$mailData ['emailIds'] = $emailId;
                                		
                                		$mailer = $serviceManager->get ( 'EmailService' );
                                		
                                		if ($mailer->sendMail ( $mailData, true )) {
                                			return $response->setContent ( Json::encode ( array (
                                					'status' => true 
                                			) ) );
                                		} else {
                                			return $response->setContent ( Json::encode ( array (
                                					'status' => false
                                			) ) );
                                		}
    }

    public function deleteCertificateAction()
    {
        $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
                                		$title = $this->params ()->fromQuery ( 'title' );
                                		
                                		if (! $id) {
                                			return $this->redirect ()->toRoute ( 'certificate' );
                                		}
                                		
                                		$view = new ViewModel ();
                                		$request = $this->getRequest ();
                                		$response = $this->getResponse ();
                                		
                                		if ($request->isPost ()) {
                                			$del = $request->getPost ( 'del', 'No' );
                                			$id = ( int ) $request->getPost ( 'id' );
                                			if ($del == 'Yes') {
                                				$userSession = new Container ( 'users' );
                                				$userid = $userSgetAssignedToession->id;
                                				$this->getCertificateTable ()->deleteCertificate ( $id, $userid );
                                			}
                                			// Redirect to list of albums
                                			return $this->redirect ()->toRoute ( 'certificate' );
                                		}
                                		
                                		$view->setTerminal ( $request->isXmlHttpRequest () );
                                		$view->setVariables ( array (
                                				'id' => $id,
                                				'title' => $title 
                                		) );
                                		return $view;
    }

    public function multiDeleteCertificateAction()
    {
        $certificateIds = $this->params ()->fromQuery ( 'certificateIds', 0 );
                                		
                                		$view = new ViewModel ();
                                		$request = $this->getRequest ();
                                		$response = $this->getResponse ();
                                		
                                		if ($request->isPost ()) {
                                			$del = $request->getPost ( 'del', 'No' );
                                			
                                			if ($del == 'Yes') {
                                				$ids = $request->getPost ( 'ids' );
                                				$ids = explode ( ",", $ids );
                                				$userSession = new Container ( 'users' );
                                				$userid = $userSession->id;
                                				$this->getCertificateTable ()->multiDeleteCertificate ( $ids, $userid );
                                			}
                                			// Redirect to list of albums
                                			return $this->redirect ()->toRoute ( 'certificate' );
                                		}
                                		
                                		$view->setTerminal ( $request->isXmlHttpRequest () );
                                		$view->setVariables ( array (
                                				'ids' => $certificateIds 
                                		) );
                                		return $view;
    }

    public function multiDeleteCertificateAlertAction()
    {
        $view = new ViewModel ();
                                		$view->setTerminal ( true );
                                		return $view;
    }

    public function viewCertificateAction()
    {
        $id = $this->params ()->fromRoute ( 'id', 0 );
                                		$file = $this->getDocRoot () . '/data/cache/certificate' . $id . '.pdf';
                                		$fileName = 'certificate' . $id . '.pdf';
                                		
                                		header ( 'Content-Disposition: inline; filename="' . $fileName . '"' );
                                		header ( 'Content-Transfer-Encoding: binary' );
                                		header ( 'Content-Length: ' . filesize ( $file ) );
                                		header ( 'Accept-Ranges: bytes' );
                                		header ( 'Content-type: application/pdf' );
                                		readfile ( $file );
    }

    public function editCertificateAction()
    {
        $certificateform = new CertificateForm ();
                                		$certificateform->get ( 'submit' )->setValue ( 'Email Certificate' );
                                		
                                		$request = $this->getRequest ();
                                		$response = $this->getResponse ();
                                		
                                		if ($request->isPost ()) {
                                			$certificate = new Certificate ();
                                			$certificateform->setData ( $request->getPost () );
                                			if ($certificateform->isValid ()) {
                                				$userSession = new Container ( 'users' );
                                				$userid = $userSession->id;
                                				$certificate->exchangeArray ( $certificateform->getData () );
                                				
                                				$lastInsertId = $this->getCertificateTable ()->saveCertificate ( $certificate, $userid ); // saving into database
                                				$response->setContent ( Json::encode ( array (
                                						'status' => 1,
                                						'lastInsertId' => $lastInsertId 
                                				) ) );
                                				return $response;
                                			}
                                		}
                                		
                                		$viewmodel = new ViewModel ( array (
                                				'form' => $certificateform 
                                		) );
                                		$viewmodel->setTerminal ( $request->isXmlHttpRequest () );
                                		return $viewmodel;
    }

    public function getDocRoot()
    {
        $sm = $this->getServiceLocator ();
                                		$config = $sm->get ( 'config' );
                                		$docroot = $config ['applicationSettings'] ['appRoot'];
                                		$this->docRoot = substr ( $docroot, 0, (strlen ( $docroot ) - 7) );
                                		return $this->docRoot;
    }

    public function getStudentTable()
    {
        if (! $this->studentTable) {
                                			$serviceManager = $this->getServiceLocator ();
                                			$this->studentTable = $serviceManager->get ( 'Student\Model\StudentTable' );
                                		}
                                		return $this->studentTable;
    }

    public function getTestTable()
    {
        if (! $this->testTable) {
                                    		$serviceManager = $this->getServiceLocator ();
                                    		$this->testTable = $serviceManager->get ( 'Test\Model\TestTable' );
                                    	}
                                    	return $this->testTable;
    }

    public function getLinkTable()
    {
        if (! $this->linkTable) {
                                    		$serviceManager = $this->getServiceLocator ();
                                    		$this->linkTable = $serviceManager->get ( 'Test\Model\LinkTable' );
                                    	}
                                    	return $this->linkTable;
    }

    public function getAssignedToAction()
    {
        $request = $this->getRequest();
                                    	$response = $this->getResponse();
                                    	$id = $this->params ()->fromRoute ( 'id', '');
                                    	if( !empty ( $id ) ) {
                                    		$testId = ( int ) $id;
                                    	}
                                    	
                                    	if ($request->isXmlHttpRequest()) { // If it's ajax call
                                    		$arrData = $this->getCertificateTable()->getAssignedTo ( $testId );
                                    	}
                                    	
                                    	$result = new JsonModel(array(
                                    			'getAssignedTo' => $arrData,
                                    			'success'=>true,
                                    	));
                                    	return $result;
    }

    public function getResultDataAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $resultId = $this->params ()->fromRoute ( 'id', '');
        
        if( !empty ( $resultId ) ) {
        	$resultId = ( int ) $resultId;
        } 
        $arrData = $this->getCertificateTable()->getResultData ( $resultId );
        if ($request->isXmlHttpRequest()) { // If it's ajax call
        	$arrData = $this->getCertificateTable()->getResultData ( $resultId );
        }
        
        $result = new JsonModel(array(
        		'resultData' => $arrData,
        		'success'=>true,
        ));
        return $result;
    }


}

