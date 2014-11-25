<?php
/**
 *@author : Jyoti Kumari,Vikash kumar

 */

namespace Question\Controller;

use Zend\Session\Container;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\Size;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\View\Model\ViewModel;
use Question\Model\Question;
use Question\Model\Category; // importing Album model
use Question\Form\QuestionForm; // importing Album Form
use Question\Model\Answer; // importing Album model
use Question\Form\AnswerForm;
use Question\Model\Csv; // importing Album model
use Question\Form\CsvForm; // importing Album Form
use Question\Form\EditquestionForm;
use Question\Form\QuestionFormCsv;
use Zend\Filter\StripTags;

class QuestionController extends AbstractActionController {
	protected $questionTable;
	protected $categoryTable;
	protected $answerTable;
	protected $category_session;
	
public function indexAction() {
		
		$category_id=$this->params()->fromQuery('id');
		
		$catid=$this->params()->fromQuery('catques');
		$questiontype=$this->params()->fromQuery('questiontype');
		$flag=$this->params()->fromQuery('flag');
		
		if($flag==0)
		{
			$type_session= new Container('questiontype');
			$category_session= new Container('category');
		
			 $category_session->catid=$catid;
			 $type_session->type=$questiontype;
			
		}
	if(isset($catid)||isset($questiontype)){
			
				if($catid=='0' && $questiontype=='2'){
					
					return new ViewModel ( array (
					'questions' => $this->getQuestionTable ()->fetchAll (),
					'categories' => $this->getCategoryTable ()->fetchAll (),
					
			) );
					
				}else{
					
					$category_session= new Container('category');
					$category_session->catid =$catid;
					return new ViewModel ( array (
					'questions' => $this->getQuestionTable ()->getQuestionbycatid ($catid),
					'categories' => $this->getCategoryTable ()->fetchAll (),
					'catid'=>$catid,
					'questiontype'=>$questiontype
			) );
				}
		}
		
		$category_session= new Container('category');
		$category_session->catid =0;
		$type_session= new Container('questiontype');
		
		$type_session->type=1;
		return new ViewModel ( array (
				
				'questions' => $this->getQuestionTable ()->fetchAll (),
				'categories' => $this->getCategoryTable ()->fetchAll () 
		) );
	}
	public function listAction() {
		$category_session= new Container('category');
		
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if($id)
		$category_session->catid =$id;
		else 
			$category_session->catid =0;
		$type_session= new Container('questiontype');
		$type_session->type=1;
		
		$question = $this->getQuestionTable ()->getCatQuestions ( $id );
		
		$request = $this->getRequest ();
		$view = new ViewModel ( array (
				'id' => $id,
				'questions' => $question 
		) );
		$view->setTerminal ( $request->isxmlHttpRequest () );
		return $view;
	}
	public function listtypeAction() {
	
		$type_session= new Container('questiontype');
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$type_session->type=$id;
		$question = $this->getQuestionTable ()->getTypeQuestions ( $id );
		$request = $this->getRequest ();
		
		$view = new ViewModel ( array (
				'id' => $id,
				'questions' => $question 
		) );
		$view->setTerminal ( $request->isxmlHttpRequest () );
		return $view;
	}
	
	public function listtypeandcategoryAction() {
		$category_session= new Container('category');
		$category_session->catid =$this->params ()->fromQuery ( 'categorytype' );;
		$type_session= new Container('questiontype');
		$type_session->type=$this->params ()->fromQuery ( 'questiontype' );
		
		$filtertype = array ();
		$filtertype ['categorytype'] = $this->params ()->fromQuery ( 'categorytype' );
		$filtertype ['questiontype'] = $this->params ()->fromQuery ( 'questiontype' );
		
		$question = $this->getQuestionTable ()->getTypeAndCategoryQuestions ( $filtertype );
		
		$request = $this->getRequest ();
		
		$view = new ViewModel ( array (
				
				'categorytype' => $filtertype ['categorytype'],
				'questiontype' => $filtertype ['questiontype'],
				'questions' => $question 
		) );
		$view->setTerminal ( $request->isxmlHttpRequest () );
		return $view;
	}
	
	//function for adding and editing question
	//answercount is number of options in database
	//id id question id passed from edit function for editing question
	//answersid for editing options
	public function addQuestionAction($answercount = '', $id = '',$answersId='') {
		$success = $this->params ()->fromRoute ( 'id' );
		$userSession = new Container ( 'users' );
		$userid = $userSession->id;
		$category_session= new Container('category');
		$category_selected=$category_session->catid ;
		
		$type_session= new Container('questiontype');
		$type_selected=$type_session->type ;
		
		
		if($this->params ()->fromQuery ( 'categorytype' )!=0 && $this->params ()->fromQuery ( 'questiontype' )!=2)
		{
		
			$category_session->catid=$this->params ()->fromQuery ( 'categorytype' );
			$category_selected=$category_session->catid;
			$type_session->type=$this->params ()->fromQuery ( 'questiontype' );
			$type_selected=$type_session->type;
		}
		else if(isset($category_session->catid) || isset($type_session->type)){

			$category_selected=$category_session->catid ;
			$type_selected=$type_session->type ;
		}else {
			
			$category_selected=0 ;
			$type_selected=1;
		}
		
		
		
		//if id is present then editing is done else addition of question is done
		if ($id) {
			$formquestion = new EditquestionForm ( $answercount );
		} else {
			$formquestion = new QuestionForm ( $this->getCategoryTable ()->fetchAll () );
		}
		
		$categories=$this->getCategoryTable ()->fetchAll ();
		$formanswer = new AnswerForm ();
		$formquestion->get ( 'submit' )->setValue ( 'Add' );
		$view = new ViewModel ();
		$request = $this->getRequest ();
		$view->setTerminal ( $request->isxmlHttpRequest () );
		
		//for deleting answers from database  which are removed while editing
		$db_ans_counter=0;
		$ansid=array ();
		$answers_db = array ();
		if($answersId){
		foreach ($answersId as $k=>$v ) {
			$answers_db[$k]=$v;
		}
		}	
		foreach($answers_db as $k=>$v){
			$key=$answers_db[$db_ans_counter]->id;
			$ansid[$key]=$answers_db[$db_ans_counter]->question_option;
		
			$db_ans_counter++;
		}
		if ($request->isPost ()) {
			
			$question = new Question ();
			
			$input_filter_question = $formquestion->getInputFilter ();
			$input_element_question = $input_filter_question->get ( 'id' );
			$input_element_question->setValue ( $request->getPost ( 'id' ) );
			
			$input_element_question = $input_filter_question->get ( 'created_by' );
			$input_element_question->setValue ( $request->getPost ( 'created_by' ) );
			
			$input_element_question = $input_filter_question->get ( 'created_on' );
			$input_element_question->setValue ( $request->getPost ( 'created_on' ) );
			
			$input_element_question = $input_filter_question->get ( 'updated_by' );
			$input_element_question->setValue ( $request->getPost ( 'updated_by' ) );
			
			$input_element_question = $input_filter_question->get ( 'updated_on' );
			$input_element_question->setValue ( $request->getPost ( 'updated_on' ) );
			
			$input_element_question = $input_filter_question->get ( 'cat_id' );
			$input_element_question->setValue ( $request->getPost ( 'cat_id' ) );
			
			$input_element_question = $input_filter_question->get ( 'type' );
			$input_element_question->setValue ( $request->getPost ( 'type' ) );
			
			$input_element_question = $input_filter_question->get ( 'description' );
			$input_element_question->setValue ( $request->getPost ( 'description' ) );
			$input_element_question = $input_filter_question->get ( 'status' );
			$input_element_question->setValue ( $request->getPost ( 'status' ) );
			$input_element_question = $input_filter_question->get ( 'points' );
			$input_element_question->setValue ( $request->getPost ( 'points' ) );
			
			if (! isset ( $id )) {
				$input_element_question = $input_filter_question->get ( 'hdnCount' );
				$input_element_question->setValue ( $request->getPost ( 'hdnCount' ) );
			} 
			// answer table validation
			$count = $request->getPost ( 'hdnCount' );
			for($i = 0; $i < $count; $i ++) {
				$answer = new Answer ();
				$input_filter_answer = $formanswer->getInputFilter ();
				$input_element_answer = $input_filter_answer->get ( 'id' );
				$input_element_answer->setValue ( $request->getPost ( 'id' ) );
				$input_element_answer = $input_filter_answer->get ( 'question_id' );
				$input_element_answer->setValue ( $request->getPost ( 'question_id' ) );
				
				$input_element_answer = $input_filter_answer->get ( 'correct' );
				$input_element_answer->setValue ( $request->getPost ( 'correct' ) );
			}
			$temp = $request->getPost ();
			$type = $temp->type;
			
			if ($type == '0') {
				for($i = 0; $i < 2; $i ++) {
					$answer = new Answer ();
					$input_filter_answer = $formanswer->getInputFilter ();
					$input_element_answer = $input_filter_answer->get ( 'id' );
					$input_element_answer->setValue ( $request->getPost ( 'id' ) );
					$input_element_answer = $input_filter_answer->get ( 'question_id' );
					$input_element_answer->setValue ( $request->getPost ( 'question_id' ) );
					
					$input_element_answer = $input_filter_answer->get ( 'correct' );
					$input_element_answer->setValue ( $request->getPost ( 'correct' ) );
				}
			}
			
			if ($input_element_question->isValid ()) {
				$questions_values = array ();
				$temp = $request->getPost ();
				$type = $temp->type;
				$description = htmlentities($request->getPost ( 'description' ));
				//for insertion in questions table 
				foreach ( $temp as $k => $v ) {
					if ($k == "description") {
						$questions_values [$k] = $description;
					} else {
						$questions_values [$k] = $v;
					}
					if ($k == "points") {
						
						unset ( $temp [$k] );
						break;
					}
					unset ( $temp [$k] );
				}
				
				/* insert into question */
				$question->exchangeArray ( $questions_values );
				$question_id = $this->getQuestionTable ()->saveQuestion ( $question, $userid );
				if (! empty ( $id )) {
					$question_id = $id;
				}
				
				// fetching answer values for insertion in question_options table
				$answers_values = array ();
				foreach ( $temp as $k => $v ) {
					$answers_values [$k] = $v;
					
					unset ( $temp [$k] );
				}
				$answers_values ['question_id'] = $question_id;
				$optionDescription = $answers_values ['optionDescription'];
				$result=array_diff_key($ansid,$optionDescription);
				
				//deleting answers from database which is removed while editing
				$answersIdtoDelete=array();
				$counter=0;
				foreach($result as $k=>$v)
				{
					$answersIdtoDelete[$counter++]=$k;
				}
				
				 if(count($result)>=1)
				{
					$this->getAnswerTable ()->deleteAnswerId ( $answersIdtoDelete );
				} 
				$checkedoption = $answers_values ['check'];
				$question_options = array ();
				//multiple type answer insertion
				if ($type == '1') {	
					$j = 1;
					$k=1;
					foreach ( $optionDescription as $key => $value ) {
						$option_value = $value;
						if (in_array ( $key, $checkedoption )) {
							$checkAns = '1';
						} else {
							$checkAns = '0';
						}
						if (! empty ( $id ))
							$question_options ['id'] = $key;
						$question_options ['question_id'] = $question_id;
						$question_options ['question_option'] = $value;
						$question_options ['correct'] = $checkAns;
						//editing answers if id is present then update answers else insert answers
						if (! empty ( $id )) {
							if($j++<= $answercount)
							{
							$answer->exchangeArray ( $question_options );
							$this->getAnswerTable ()->saveMultipleEditAnswer ( $answer );
							}
							else
							{
							$answer->exchangeArray ( $question_options );
							$this->getAnswerTable ()->saveAnswer ( $answer );
							}
							
						} else
							{
							$answer->exchangeArray ( $question_options );
							$this->getAnswerTable ()->saveAnswer ( $answer );
							}
						}
					} else if ($type == '0') {
						//true flase insertion
					foreach ( $optionDescription as $key => $value ) {
						$option_value = $value;
						if (in_array ( $key, $checkedoption )) {
							$checkAns = '1';
						} else {
							$checkAns = '0';
						}
						if (! empty ( $id ))
							$question_options ['id'] = $key;
						$question_options ['question_id'] = $question_id;
						$question_options ['question_option'] = $value;
						$question_options ['correct'] = $checkAns;
						if (! empty ( $id )) {
							$answer->exchangeArray ( $question_options );
							$this->getAnswerTable ()->saveMultipleEditAnswer ( $answer );
							if($i < $ansCount)
							{
								$answer->exchangeArray ( $question_options );
								$this->getAnswerTable ()->saveAnswer ( $answer );
							}
							
						} else {
							$answer->exchangeArray ( $question_options );
							$this->getAnswerTable ()->saveAnswer ( $answer );
						}
					}
				}
				if (! $id) {//remain on add question page after adding questions
				return $this->redirect ()->toRoute ( 'question', array (
							'action' => 'addQuestion',
							'id' => '1' 
					) );
				} else {
					return $this->redirect ()->toRoute ( 'question', array (
							'action' => 'index' 
					) );
				}
			}
		}
		
		$view->setVariables ( array (
				'form' => $formquestion,
				'success' => $success ,
				'category_selected'=>$category_selected,
				'categories'=>$categories,
				'type_selected'=>$type_selected
		) );
		
		return $view; 
	}
	
	//function for edit question
	public function editAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if (! $id) {
			return $this->redirect ()->toRoute ( 'question', array (
					'action' => 'addQuestion' 
			) );
		}
		try {
			$question = $this->getQuestionTable ()->getQuestion ( $id );
			$answers = $this->getAnswerTable ()->getAnswer ( $id );
			$answersId = $this->getAnswerTable ()->getAnswersId ( $id );
			
		} catch ( \Exception $ex ) {
			return $this->redirect ()->toRoute ( 'question', array (
					'action' => 'index' 
			) );
		}
		$cat_id = ( int ) $question->cat_id;
		$type = ( int ) $question->type;
		$i = 0;
		if ($type == 1)
			$i = count ( $answers );
		else
			$i = 1;
		$form = new EditquestionForm ( $i );
		$form->bind ( $question );
		
		$form->get ( 'submit' )->setAttribute ( 'value', 'Edit' );
		$request = $this->getRequest ();
		$formanswer = new AnswerForm ();
		//passing values to add function for editing
		$this->addQuestionAction ( $i, $id,$answersId );
		
		$view = new ViewModel ( array (
				'type' => $type,
				'cat_id' => $cat_id,
				'id' => $id,
				'form' => $form,
				'answers' => $answers,
				'categories' => $this->getCategoryTable ()->fetchAll () 
		) );
		$view->setTerminal ( $request->isxmlHttpRequest () );
		return $view;
	}
	
	//view question details about answers and correct options
	public function viewAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$question = $this->getQuestionTable ()->getQuestion ( $id );
		$answer = $this->getAnswerTable ()->getAnswer ( $id );
		$request = $this->getRequest ();
		$view = new ViewModel ( array (
				'id' => $id,
				'question' => $question,
				'answer' => $answer,
				'categories' => $this->getCategoryTable ()->fetchAll () 
		) );
		$view->setTerminal ( $request->isxmlHttpRequest () );
		return $view;
	}
	
	//delete question action popup
	public function deleteAction() {
		$categoryid = $this->params ()->fromQuery ( 'categoryselected' );
		$questiontype = $this->params ()->fromQuery ( 'questiontype' );
		$questionid = $this->params ()->fromQuery ( 'questionid' );
		$viewmodel = new ViewModel ( array (
				'categoryid' => $categoryid,
				'questiontype'=>$questiontype,
				'questionid'=>$questionid,
				
		
		) );
		$viewmodel->setTerminal ( true );
		return $viewmodel;
	}
	//delete question from database
	public function deleteSingleSelectedQuestionAction()
	{
		$request = $this->getRequest ();
		$questionid = $this->params ()->fromRoute ( 'id' );
		$this->getQuestionTable ()->deleteQuestion ( $questionid );
		$viewmodel = new ViewModel ();
		$viewmodel->setTerminal ( true );
		return $viewmodel;
	}
	
	//alert for deleting multiple question
	public function muldeletequestionalertAction() {
		$viewmodel = new ViewModel ();
		$viewmodel->setTerminal ( true );
		return $viewmodel;
	}
	//view for multiple delete action
	public function muldeletequestionAction() {
		$categoryid = $this->params ()->fromQuery ( 'categoryselected' );
		$questiontype = $this->params ()->fromQuery ( 'questiontype' );
		$viewmodel = new ViewModel ( array (
				'categoryid' => $categoryid,
				'questiontype'=>$questiontype,
	
		) );
		$viewmodel->setTerminal ( true );
		return $viewmodel;
	}
	//deleting all selected questions
	public function deleteSelectedQuestionAction() 
	{
		$request = $this->getRequest ();
		$questionid = $this->params ()->fromQuery ( 'id' );
		$this->getQuestionTable ()->deleteallQuestion ( $questionid );
		$viewmodel = new ViewModel ();
		$viewmodel->setTerminal ( true );
		return $viewmodel;
	}
	
	//csv uploading and import function
	public function csvAction() {
		$formcsv = new CsvForm ();
		$view = new ViewModel ();
		$formcsv->get ( 'submit' )->setValue ( 'import' );
		$request = $this->getRequest ();
		$view->setTerminal ( $request->isxmlHttpRequest () );
		
		$view->setVariables ( array (
				'form' => $formcsv 
				) );
		return $view;
	}
	
	//uploading csv file in /public/csvuploads folder
	public function csvimportAction() 	// Funtion to improt csv file by browser
	{
		$form = new CsvForm ();
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$File = $this->params ()->fromFiles ( 'csv' );
			$adapter = new \Zend\File\Transfer\Adapter\Http ();
			$size = new \Zend\Validator\File\Size ( array (
					'min' => 1 
					) );
			$adapter->setValidators ( array ($size), $File ['name'] );
			if (! $adapter->isValid ()) {
				$dataError = $adapter->getMessages ();
				$error = array ();
				foreach ( $dataError as $key => $row ) {
					$error [] = $row;
				}
				$form->setMessages ( array (
						'csv' => $error 
				) );
			} else {
				$publicDir = getcwd () . '/public/csvuploads';
				$adapter->setDestination ( $publicDir );
				if ($adapter->receive ( $File ['name'] )) {
					$data ['csv'] = $File ['name'];
					$this->addcsvAction ( $data );
				}
			}
		}
		return $this->redirect ()->toRoute ( 'question' );
	}
	//inserting csv file values into questions and question_options database
	public function addcsvAction($arrArgument = '') {
		$userSession = new Container ( 'users' );
		$userid = $userSession->id;
		$file = $arrArgument ['csv'];
		$fileName = getcwd () . '/public/csvuploads/' . $file;
		$handle = fopen ( $fileName, "r" );
		$row = 0;
	
		while ( $data = fgetcsv ( $handle, 10000, "\\", "'","\\" ) ) // loop through the csv file and insert into database
		{
			$row++;
			if ($row > 1) {
				if ($data [0]) {
					$data = explode ( ";", $data [0] );
					$chkCat = $this->getCategoryTable ()->getCategoryForCsv ( $data [2] );
					
					if ($chkCat == '1') {
						
						$category = new Category ();
						$category_values = array ();
						$category_values ['name'] = trim($data [2]);
						$category_values ['updated_on'] = $category_values ['created_on'] = date ( 'Y-m-d h:i:s A', time () );
						$category_values ['updated_by'] = $category_values ['created_by'] = $userid;
						$category_values ['status'] = '0';
						$category->exchangeArray ( $category_values );
						$catId = $this->getCategoryTable ()->saveCategory ( $category );
					} else {
						$catId = $this->getCategoryTable ()->getCategoryId ( $data [2] );
					}
					
					$question = new Question ();
					$question_values = array ();
					$question_values ['cat_id'] = $catId;
					$question_values ['description'] = trim($data [1]);
					$question_values ['updated_on'] = $question_values ['created_on'] = date ( 'Y-m-d h:i:s A', time () );
					$question_values ['updated_by'] = $question_values ['created_by'] = $userid;
					$question_values ['status'] = trim($data [14]);
					$question_values ['points'] = trim($data [12]);

				
					$question_values ['type'] = trim($data [13]);
					
					$question->exchangeArray ( $question_values );
					$question_id = $this->getQuestionTable ()->saveQuestion ( $question, $userid );
					
					if($data [13]=='1')
					{
					for($x = 3; $x <= 10; $x ++) {
						
						if ($data [$x] != '') {
							
							if ($data [$x] == trim($data [11])){
								$answer = new Answer ();
								$answer_options = array ();
								$answer_options ['question_id'] = $question_id;
								$answer_options ['question_option'] = trim($data [$x]);
								$answer_options ['correct'] = '1';
								$answer->exchangeArray ( $answer_options );
								$this->getAnswerTable ()->saveAnswer ( $answer );
							} 

							else {
								
								$answer = new Answer ();
								$answer_options = array ();
								$answer_options ['question_id'] = $question_id;
								$answer_options ['question_option'] = trim($data [$x]);
								$answer_options ['correct'] = '0';
								$answer->exchangeArray ( $answer_options );
								$this->getAnswerTable ()->saveAnswer ( $answer );
							}
						}
					}
					}else if($data [13]=='0')
					{
						for($x = 3; $x <= 4; $x ++) {
						
							if ($data [$x] != '') {
									
								if ($data [$x] == trim($data [11])) {
									$answer = new Answer ();
									$answer_options = array ();
									$answer_options ['question_id'] = $question_id;
									$answer_options ['question_option'] = trim($data [$x]);
									$answer_options ['correct'] = '1';
									$answer->exchangeArray ( $answer_options );
									$this->getAnswerTable ()->saveAnswer ( $answer );
								}
						
								else {
						
									$answer = new Answer ();
									$answer_options = array ();
									$answer_options ['question_id'] = $question_id;
									$answer_options ['question_option'] = trim($data [$x]);
									$answer_options ['correct'] = '0';
									$answer->exchangeArray ( $answer_options );
									$this->getAnswerTable ()->saveAnswer ( $answer );
								}
							}
						}
					}
				}
			}
		}
		
	}
	//user table
	public function getUserTable() {
		if (! $this->userTable) {
			$serviceManager = $this->getServiceLocator ();
			$this->userTable = $serviceManager->get ( 'User\Model\UserTable' );
		}
		return $this->userTable;
	}
	//question table
	public function getQuestionTable() {
		if (! $this->questionTable) {
			$serviceManager = $this->getServiceLocator ();
			$this->questionTable = $serviceManager->get ( 'Question\Model\QuestionTable' );
		}
		return $this->questionTable;
	}
	//category table
	public function getCategoryTable() {
		if (! $this->categoryTable) {
			$serviceManager = $this->getServiceLocator ();
			$this->categoryTable = $serviceManager->get ( 'Question\Model\CategoryTable' );
		}
		return $this->categoryTable;
	}
	//answer table
	public function getAnswerTable() {
		if (! $this->answerTable) {
			$serviceManager = $this->getServiceLocator ();
			$this->answerTable = $serviceManager->get ( 'Question\Model\AnswerTable' );
		}
		return $this->answerTable;
	}
}