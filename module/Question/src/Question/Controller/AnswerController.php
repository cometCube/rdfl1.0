<?php
/**
 *@author : Jyoti Kumari
 *@date :10.7.14


 */

namespace Question\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Question\Model\Answer;     // importing Album model
use Question\Form\AnswerForm;  // importing Album Form

use Question\Model\AnswerTable;

class AnswerController extends AbstractActionController
{
    protected $answerTable;

    public function indexAction() 
    {
        return new ViewModel(array(
            'answer' => $this->getAnswerTable()->fetchAll(),
        ));
    }

   
    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $answer = $this->getAnswerTable()->getAnswer($id);
        $request = $this->getRequest();
		 $result = "<table>";
         $result .= "<tr><td>".$answer->question_option."</td></tr>";
         $result .= "</table>";
         $view = new ViewModel(array(
            'id' => $id,
            'ans' => $result,
        ));
        $view-> setTerminal($request->isXmlHttpRequest());
		return $view;
    }
   
   //to fetch all answers of question
    public function getAnswerTable()
    {
        if (!$this->answerTable) {
            $serviceManager   = $this->getServiceLocator();
            $this->answerTable = $serviceManager->get('Answer\Model\AnswerTable');
        }
        return $this->answerTable;
    }
    

}
