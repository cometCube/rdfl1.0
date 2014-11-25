<?php

namespace Student\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Student\Form\StudentForm;
use Student\Form\StudentFormFilter;
use Student\Model\Student;
use Student\Model\StudentTable;
use Zend\Json\Json;
use Encdec\Encdec\EncdecService;

/**
 * Student class which is resopsible for allowing students to give test and get result
 * 
 */
class StudentController extends AbstractActionController
{
    protected $StudentTable = null;
    protected $clientId = null;
    protected $linkId = null;
    protected $code = null;
    protected $password;

    
    public function indexAction()
    {
        return new ViewModel();
    }
   
    
    public function instructionAction()
    {
       
        $quizSession = new Container('quiz');

        if ($quizSession->code  != 'preview') {
            if($quizSession->testDetails[1] == null){
                 
                return $this->redirect()->toRoute('student', array(
                'action' => 'quiz',
                'clientid' => $quizSession->clientId, 
                'linkid' => $quizSession->linkId,
                'code' => $quizSession->code,
            ));
            }
        }

        return new ViewModel();
    }
    
    
    
    /**
     * Quiz action returns student login form by default
     * and if request is post then student data is saved and redirects to instruction page.
     * 
     * @access public
     * @param 	
     * @return returns student login page(if request not post)
     * @author Divesh Pahuja, Dhirendra Pandey
     */
    public function quizAction()
    {

    	$request     = $this->getRequest(); // getting current request object
        $quizSession = new Container('quiz');
        $quizSession->getManager()->getStorage()->clear('quiz');
        $quizSession->offsetUnset('quiz');
        unset($quizSession->elapsed);

        
        $studentform                 = new StudentForm();
        $quizSession->clientId       = $this->clientId = $this->params()->fromRoute('clientid');
        $quizSession->linkId         = $this->linkId = $this->params()->fromRoute('linkid');
        $quizSession->code           = $this->code = $this->params()->fromRoute('code');
        $resultset                   = $this->getStudentTable()->quizDetails($this->clientId, $this->linkId, $this->code)->toArray();
        $quizSession->testDetails    = array();
        $quizSession->testDetails[0] = $resultset;

        $this->password=$resultset[0]['password'];   

        
        if ($request->isPost()) {

            $student = new Student();
            $studentform->setInputFilter($student->getInputFilter());
            $postArr = array_keys((array)$request->getPost());
            $postArr = array_diff($postArr, array('pass', 'login'));
            $studentform->setValidationGroup ($postArr);
            $studentform->setData($request->getPost()); // setting requested data to form object
            $response = $this->getResponse();
            if ($studentform->isValid()) {
                $student->exchangeArray($studentform->getData());
               
                $stdarr                      = (array) $this->getStudentTable()->saveStudent($student);
                $quizSession->testDetails[1] = $stdarr;
                return $this->redirect()->toRoute('student', array(
                    'action' => 'instruction'
                ));
            } else {
                return new ViewModel(array(
                    'form' => $studentform,
                    'arrData' => $resultset
                ));
            }
        } else {
            if ($this->code != 'preview') {
                $data        = $this->getStudentTable()->getLinkCodeTime($this->code, $this->linkId);
                $currentTime = (array) new \DateTime(date('Y-m-d H:i:s'));
                $currentTime = $currentTime['date'];
                if ($data['showfrom'] <= $currentTime && $data['showuntill'] >= $currentTime) {
                    return new ViewModel(array(
                        'form' => $studentform,
                        'arrData' => $resultset
                    ));
                } else {
                    return $this->redirect()->toRoute('student', array(
                        'action' => 'linkExpire'
                    ));
                }
            } else {
                if ($this->code == 'preview') {

                    $as=$this->getStudentTable()->delete_preview_rows();


                    return $this->redirect()->toRoute('student', array(
                        'action' => 'instruction',
                    ));
                }
            }
        }
        return new ViewModel(array(
            'form' => $studentform,
            'arrData' => $resultset
        ));
    }
    
    
    /**
     * getStudentTable action to get student model object through service manager
     * it checks if object instance exists it returns else create and returns.
     * 
     * @access public
     * @param
     * @return student table object
     * @author Dhirendra Pandey
     */
    public function getStudentTable()
    {
        if (!$this->StudentTable) {
            $serviceManager     = $this->getServiceLocator();
            $this->StudentTable = $serviceManager->get('Student\Model\StudentTable');
        }
        return $this->StudentTable;
    }
    
    
    /**
     * quizStart action  to set quiz details in session, 
     * get elapsed time before test starts
     * 
     * @access public
     * @param
     * @return redirects to give test action
     * @author Divesh Pahuja
     */
    public function quizStartAction()
    {
        $quizSession = new Container('quiz');
       
        $quizSession->offsetSet('start', '1');
        $quizSession->offsetUnset('flags');
        $quizSession->flags = array();
        $quizSession->offsetUnset('startTime');
        
        if ($quizSession->code === 'preview') {
            $studentId   = 0;
            $ElapsedTime = 0;
        } else {
            $studentId   = $quizSession->testDetails[1]['id'];
            $data        = array(
                $studentId,
                $quizSession->code
            );
            
            $ElapsedTime = $this->getStudentTable()->loadAttemptTime($data); 
            //get time elapsed if test already given
        }
        $quizSession->testDetails[0][0]["time"] *= 60;
        $quizSession->testDetails[0][0]["time"] = $quizSession->testDetails[0][0]["time"] - $ElapsedTime;

        if ($quizSession->testDetails[0][0]["time"] === 0) {
            $quizSession->testDetails[0][0]["given"] = '1';
        }
        $quizData               = $quizSession->testDetails[0][0];
        $arrQueId               = $this->getStudentTable()->getArrQuestion($quizData);
        $quizSession->arrQuesId = $arrQueId; 					//Storing current question id in session
        $startTime              = date_create(date("H:i:s"));
        $quizSession->startTime = $startTime; 					//storing quiz start time in session
        
        return $this->redirect()->toRoute('student', array(
            'action' => 'giveTest'
        ));
    }
    
    /**
     * link Expire Page
     *
     * @access public
     * @param
     * @return returns view model
     * @author Divesh Pahuja
     */
    public function linkExpireAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    /**
     * Give Test action is responsible for:	
     * 	1. Check Mode, whether it is preview or normal.
     * 	2. Set time remaning
     *  3. Load Question data from question id
     *  4. Encrypt counter for next back button.
     *
     * @access public
     * @param
     * @return returns test page
     * @author Divesh Pahuja
     */
    public function giveTestAction()
    {
       
        $serviceManager = $this->getServiceLocator();
        $encrypt        = $serviceManager->get('EncdecService');
        
        $quizSession    = new Container('quiz');
        

        if(!$quizSession->offsetExists('testDetails')){
            return $this->redirect()->toRoute('student', array(
            'action' => 'linkExpire'
        ));
        }
        if ($quizSession->code == 'preview') {
            $studentId = 0;
        } else {
            $studentId = $quizSession->testDetails[1]['id'];
        }
        $quizData = $quizSession->testDetails[0][0];
       

        $quizData['time'];
        $arrQueId                   = $quizSession->arrQuesId;
        $flagForNextButton          = 0;
        $startTime                  = $quizSession->startTime;
        $startTimeDate              = $startTime->format('Y-m-d H:i:s');
        $quizSession->startTimedate = $startTimeDate;
        $currentTime                = new \DateTime((date('H:i:s')));
        $interval                   = date_diff($currentTime, $startTime);
        $lapse                      = $interval->s + $interval->i * 60 + $interval->h * 3600;
        $quizSession->elapsed       = $lapse;
        $quizData['totalTime']      = $quizData['time'];
        $quizData['time']           = $quizData['time'] - $lapse;
        $lengthQuestionIdArray      = count($arrQueId);


         //\Zend\Debug\Debug::dump($quizData);

        $counter                    = $this->params()->fromRoute('clientid');
        if ($this->params()->fromRoute('clientid') != null) {
            $counter = $this->params()->fromRoute('clientid');
            $counter = (String) $encrypt->mntdecodeAlgo($counter);
        } else {
            $counter = 0;
        }
        $quizSession->currentQuestionId = $arrQueId[$counter];
        $data                           = array(
            $studentId,
            $arrQueId[$counter],
            $quizSession->code
        );
        $questionData                   = $this->getStudentTable()->loadQuestion($data);
        if ($counter < $lengthQuestionIdArray - 1) {
            $returnData = array(
                $quizData,
                $questionData[0],
                $counter + 1,
                $flagForNextButton,
                $questionData[1]
            );
        } elseif ($counter < $lengthQuestionIdArray) {
            $flagForNextButton = 1;
            $returnData        = array(
                $quizData,
                $questionData[0],
                $counter + 1,
                $flagForNextButton,
                $questionData[1]
            );
        }
        
        $flag        = 0;
        $arr_check[] = str_replace(',', '', $returnData[1]['correct_options']);
        $str         = $arr_check[0];
        $i           = 0;
        while ($i < strlen($str)) {
            if ($str[$i] == 1) {
                $flag += 1;
            }
            $i++;
        }
        unset($returnData[1]['correct_options']);
        $returnData[1]['correct_flag']            = $flag;
        $quizSession->flags[$returnData[1]['id']] = $flag;
        $returnData['startDate']                  = $startTimeDate;
        return new ViewModel(array(
            'arrData' => $returnData,
            'encryptobj' => $encrypt
        ));
    }
    
    /**
     * checkStudentStatus action checks if student already exists in database
     * if exists, it returns the student details which is set in session
     *
     * @access public
     * @param
     * @return returns flag or student details in json
     * @author Dhirendra Pandey
     */
    public function checkStudentStatusAction()
    {
        $response      = $this->getresponse();
        $student_email = $this->params()->fromPOST('email');
        $studentStatus = $this->getStudentTable()->getStudent($student_email);
        if ($studentStatus != false) {
            $response->setContent(JSON::encode(array(
                'flag' => '1',
                'student_array' => $studentStatus
            )));
        } else {
            $response->setContent(JSON::encode(array(
                'flag' => '0'
            )));
        }
        return $response;
    }

    public function checklinkpasswordAction()
    {
        echo $this->password;
        $quizSession = new Container('quiz');
        $response      = $this->getresponse();
        $this->password=$quizSession->testDetails[0][0]['password'];
        
       $student_password = $this->params()->fromPOST('pass');

       if ($student_password == $this->password) {
            $response->setContent(JSON::encode(array(
                'flag' => '1',
                
            )));
        } else {
            $response->setContent(JSON::encode(array(
                'flag' => '0'
            )));
        }
        return $response;
    }
    
    /**
     * this action save selected option of question by student while giving test
     *
     * @access public
     * @param
     * @return returns status if option is saved or not in json
     * @author Dhirendra Pandey
     */
    public function addSelectedOptionAction()
    {
        $quizSession                = new Container('quiz');
        $startTime                  = $quizSession->startTime;
        $startTimeDate              = $startTime->format('Y-m-d H:i:s');
        $quizSession->startTimedate = $startTimeDate;
        $currentTime                = new \DateTime((date('H:i:s')));
        $interval                   = date_diff($currentTime, $startTime);
        $lapse                      = $interval->s + $interval->i * 60 + $interval->h * 3600;
        //$optionID=$this->params()->fromPOST('option_Id');
        if ($quizSession->code == 'preview') {
            $studentId = 0;
        } else {
            $studentId = $quizSession->testDetails[1]['id'];
        }
        $currentQuestionId = $quizSession->currentQuestionId;
        if (isset($_POST['option_Id']) && !empty($_POST['option_Id'])) {
            $selectedOptionId = $this->params()->fromPOST('option_Id');
        }
        $linkCode = $quizSession->code;
        $data     = array(
            $studentId,
            $currentQuestionId,
            $selectedOptionId,
            $linkCode,
            $lapse
        );
        $return   = $this->getStudentTable()->addSelectedOption($data);
        $response = $this->getResponse();
        $response->setContent(Json::encode($return));
        return $response;
    }
    public function elapsedTimeEntryAction()
    {
        
        $quizSession                = new Container('quiz');
        $startTime                  = $quizSession->startTime;
        $startTimeDate              = $startTime->format('Y-m-d H:i:s');
        $quizSession->startTimedate = $startTimeDate;
        $currentTime                = new \DateTime((date('H:i:s')));
        $interval                   = date_diff($currentTime, $startTime);
        $lapse                      = $interval->s + $interval->i * 60 + $interval->h * 3600;
        //$optionID=$this->params()->fromPOST('option_Id');
        if ($quizSession->code == 'preview') {
            $studentId = 0;
        } else {
            $studentId = $quizSession->testDetails[1]['id'];
        }
        //$currentQuestionId = $quizSession->currentQuestionId;
       /* if (isset($_POST['option_Id']) && !empty($_POST['option_Id'])) {
            $selectedOptionId = $this->params()->fromPOST('option_Id');
        }*/
        $linkCode = $quizSession->code;
        $time_lim=$quizSession->testDetails[0][0]['time']*60;
        $data     = array(
            $studentId,
            $linkCode,
            $lapse,
            $time_lim,
        );
        $return   = $this->getStudentTable()->elapsed_time_entry($data);

        $response = $this->getResponse();
        $response->setContent(Json::encode($return));
        return $response;  
    }
    
    /**
     * this action calculates score and percentage, generate result
     * and save result processed when test ends
     * @access public
     * @param
     * @return returns result page, with last inserted data if not preview mode
     * @author Divesh Pahuja
     */
    public function resultProcessAction()
    {
    	$no_of_attempts=1;
        
        $quizSession = new Container('quiz');
        $lapse       = $quizSession->testDetails[0][0]['time'];
        
        if ($quizSession->code == 'preview') {
            $studentId = 0;
        } else {
            $studentId = $quizSession->testDetails[1]['id'];
        }
        $currentQuestionId = 0;
        $selectedOptionId  = 0;
        $linkCode          = $quizSession->code;
        $quizData          = $quizSession->testDetails[0][0];
        $testName          = $quizData['testName'];
        $totalQuestions    = $quizData['queTotal'];
        $returnData        = "";
        if ($quizSession->code == 'preview') {
            $studentId = 0;
            $quesids = 0;
        } else {
            $studentId = $quizSession->testDetails[1]['id'];
            $quesids   = implode(",",$quizSession->arrQuesId);
        }
        $Timerdata    = array(
            $studentId,
            $currentQuestionId,
            $selectedOptionId,
            $linkCode,
            $lapse,
            $no_of_attempts
        );

        $return       = $this->getStudentTable()->addSelectedOption($Timerdata);
       
        $data         = array(
            $linkCode,
            $studentId,
        	$quesids	
        );
        $marks        = 0;
        $totalmarks   = 0;
        $correctCount = $this->getStudentTable()->resultProcessing($data);
        $totalmarks  = $this->getStudentTable()->getTestTotalMarks($data);
        
        foreach ($correctCount as $key) {
            $count          = $key['correct_counts'] . "<br>";
            $cCount         = explode(',', $count);
            $correct_counts = count($cCount);
            if(isset($quizSession->flags[$key['quesid']])){
	            if ($quizSession->flags[$key['quesid']] == $correct_counts) {
	                $marks += $key['points'];
	            }
            }
        }
      
        if ($quizSession->code != 'preview') {
            $returnData            = array(
                $testName,
                $totalQuestions,
                $marks
            );
            $quizSession->testNaam = $returnData[0];
            $currentTime           = new \DateTime((date('H:i:s')));
            $data_1                = array(
                $quizSession->linkId,
                $linkCode,
                $studentId,
                $returnData,
                $lapse,
                $quizSession->startTimedate,
                $currentTime,
                $totalmarks
            );
            if (!isset($quizSession->testDetails[0][0]['given'])) {
                $resultData = $this->getStudentTable()->result_store($data_1);
            }
        } else {
            return new ViewModel();
        }
        $quizSession->getManager()->getStorage()->clear('quiz');
      //  unset($quizSession['quiz']);
        $resultData = $this->getStudentTable()->fetch_data($data_1);
        return new ViewModel(array(
            'arrData' => @$resultData
        ));
    }
    public function closeWindowAction()
    {
        $flag=1;
        $return =$this->getStudentTable()->closeWindow($flag);
        $response = $this->getResponse();
        $response->setContent(Json::encode($return));
        return $response;  
    }

}
