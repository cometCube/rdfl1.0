<?php
/*
* @author : Leena,Vijay
* @desc : Test Questions Entity for Test module
* @created on : 01-07-2014
* ---------------------------------------------
* @modified on :
*
*
*
*/

namespace Test\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;


class TestQuestionsTable 
{
	protected $table = 'assigned_questions';
	protected $adapter;
	public $resultSetPrototype;

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->resultSetPrototype = new ResultSet ();
	}

	public function fetchAll() 
	{
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}
	
	/* load all questions of a test */
	public function fetchQuestions($id) 
	{
		$test_id = ( int ) $id;
		$testName = '';
		$status = 0;
		
		$where = new Where ();
		//$where(array('assignedQuesTabl.test_id'=>$test_id));
		
		$sql = new Sql ( $this->adapter );
		$select = $sql->select ()
		              ->from ( array ('assignedQuesTabl' => 'assigned_questions' ) )
		              ->columns ( array ('testId' => 'test_id',
										 'testQuesId' => 'id',
										 'quesId' => 'ques_id',
										 'quesStatus' => 'status' ) )
					  ->join ( array ('questionTabl' => 'questions' ), 'ques_id = questionTabl.id', 
					  		   array ('quesDesc' => 'description',
									  'quesCatId' => 'cat_id',
					  		   		  'quesPoints' => 'points', 
					  		          'quesCreatedBy' => 'created_by',
					  		   		  'quesCreatedOn' => 'created_on',
					  		   		  'quesUpdatedBy' => 'updated_by',
					  		   		  'quesUpdatedOn' => 'updated_on',
					  		          'questionStatus' => 'status',) )
					  ->join ( array ('categoryTabl' => 'category' ), 'cat_id = categoryTabl.id', 
					  		   array ('categoryDesc' => 'name' ) )
					  ->join ( array ('testTabl' => 'test' ), 'test_id = testTabl.id', 
					  		   array ('testDesc' => 'name' ) )
					  ->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
					  		   array ('createdby' => new \Zend\Db\Sql\Expression(
					  		   		  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
					  		   		  'updatedby' => new \Zend\Db\Sql\Expression(
					  		   		  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
					  ->where ( array('assignedQuesTabl.test_id'=>$test_id, 
					  				  'assignedQuesTabl.status'=>$status) )
					  ->order('questionTabl.created_on DESC');
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
		if(empty($result)){
			$where = new Where();
			$where->equalTo ( 'id', $test_id );
			$select = $sql->select ()
			              ->from ( array ('testTabl' => 'test' ) )
						  ->columns ( array ('testId' => 'id',
											 'testDesc' => 'name'
										    ) )
						  ->where($where);
			$statement = $sql->prepareStatementForSqlObject ( $select );
			$resultTest = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray (); 
			foreach ($resultTest as $data){
				$testName = $data['testDesc'];
			}
		}
		else 
		{
			foreach ($result as $data){
				$testName = $data['testDesc'];
			}
		}
		$session = new \Zend\Session\Container('test');
		$session->testId = $test_id;
		$session->testDesc = $testName;
		return $result;
	}
	
	/* list all questions that are not added to a test */
	public function listQuestions($testid) 
	{
		$test_id = ( int ) $testid;
		
		$status = 0;
		$sql = new Sql ( $this->adapter );
		$where = new Where ();
		
		/*select question id's for selected test id,for inputting to sub-query defined next*/
		$stmtSelectQuesIds = $sql->select ()
		               ->from ( array ('assignedQuesTabl' => 'assigned_questions' ) )
		               ->columns ( array ('assignedQuesId' => 'ques_id' ) )
		               ->where ( array ('assignedQuesTabl.test_id' => $test_id,
									    'assignedQuesTabl.status' => $status ) );
		               
		$statementSelectQuesIds = $sql->prepareStatementForSqlObject ( $stmtSelectQuesIds );
		$resultselectQuesIds = $this->resultSetPrototype->initialize ( $statementSelectQuesIds->execute () )->toArray ();
		$quesids[] = 0;
		array_pop($quesids);
		foreach ($resultselectQuesIds as $data){
			array_push($quesids,$data['assignedQuesId']);
		}
		/*main query to select questions that are already not included in the test*/
		$select = $sql->select ()
					  ->from ( array ('questionsTabl' => 'questions' ) )
					  ->columns ( array ('quesId' => 'id',
										 'quesDesc' => 'description',
										 'quesCatid' => 'cat_id',
					  					 'quesPoints' => 'points',
										 'quesStatus' => 'status' ) )
					  ->join ( array ('categoryTabl' => 'category' ), 'cat_id = categoryTabl.id', 
					  		   array ('catDesc' => 'name' ) );
					  
					  if(!empty($resultselectQuesIds)){
					  $select->where ( new \Zend\Db\Sql\Predicate\PredicateSet
					  		    (array 
                                   (new \Zend\Db\Sql\Predicate\NotIn ( 'questionsTabl.id', $quesids),
					  		        new \Zend\Db\Sql\Predicate\Operator('questionsTabl.status','=',$status)
					               )
					  		       //\Zend\Db\Sql\Predicate\PredicateSet::OP_AND 
					            )
					          );
					  }else{
					  	$select->where ( new \Zend\Db\Sql\Predicate\PredicateSet
					  			(array(new \Zend\Db\Sql\Predicate\Operator('questionsTabl.status','=',$status))));
					  }
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
		return $result;
	}
	
	/*add selected questions to the test*/
	public function addSelectedQuesToTest($questionIds)
	{
		$session = new \Zend\Session\Container('test');
		$test_id = $session->testId;
		$statusActive = 0;
		$statusInactive = 1;
		$count = 0;
		$sql = new Sql ( $this->adapter );
		foreach ($questionIds as $data){
			
			$stmtSelectQuesIds = $sql->select ()
						  		     ->from ( array ('assignedQuesTabl' => 'assigned_questions' ) )
						             ->columns ( array ('quesId' => 'id', ) )
						             ->where(array('test_id' => $test_id,'ques_id' => $data));
			$statementSelectQuesIds = $sql->prepareStatementForSqlObject ( $stmtSelectQuesIds );
			$resultselectQuesIds = $this->resultSetPrototype->initialize ( $statementSelectQuesIds->execute ())->toArray();
			//\Zend\Debug\Debug::dump($resultselectQuesIds);die;			
			if(empty($resultselectQuesIds)){
				/*select question id's for selected test id,for inputting to sub-query defined next*/
				$stmtaddQuesIdsToTest =$sql->insert('assigned_questions')
										   ->columns(array('test_id','ques_id','status'))
										   ->values(array('test_id' => $test_id,'ques_id' => $data,'status' => $statusActive));
				$addQuesIds = $sql->prepareStatementForSqlObject ( $stmtaddQuesIdsToTest );
				$result = $this->resultSetPrototype->initialize ( $addQuesIds->execute () );
			}else{
				$stmtdeleteTestQuesid = $sql->update('assigned_questions')
											->set(array('status' => $statusActive))
											->where(array('test_id' => $test_id,'ques_id' => $data));
				$deleteQuesIds = $sql->prepareStatementForSqlObject ( $stmtdeleteTestQuesid );
				$result = $this->resultSetPrototype->initialize ( $deleteQuesIds->execute () );
			}
		}
		return $result;
	}
	
	/*get test content for link & setting generation*/
	public function testContent($testId)
	{
		$test_id = ( int ) $testId;
		$testName = '';
		$status = 0;
		
		$where = new Where ();
		//$where(array('assignedQuesTabl.test_id'=>$test_id));
		
		$sql = new Sql ( $this->adapter );
		$select = $sql->select ()
					  ->from ( array ('assignedQuesTabl' => 'assigned_questions' ) )
					  ->columns ( array ('testId' => 'test_id',
										 'testQuesId' => 'id',
										 'quesId' => 'ques_id',
										 'quesStatus' => 'status' ) )
					  ->join ( array ('questionTabl' => 'questions' ), 'ques_id = questionTabl.id',
						       array ('questionStatus' => 'status','questionCreatedOn' => 'created_on') )
					  ->where ( array('assignedQuesTabl.test_id'=>$test_id,
							  		  'assignedQuesTabl.status'=>$status) )
					  ->order('questionTabl.created_on DESC');
		$statement = $sql->prepareStatementForSqlObject ( $select );
	    $result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
	    return $result;
	}
	
	/*single-delete questions from a test*/
	public function deleteTestQues($quesId)
	{
		$session = new \Zend\Session\Container('test');
		$test_id = $session->testId;
		$status = 1;
		$sql = new Sql ( $this->adapter );
		$where = new Where();
		
		/*select question id's for selected test id,for inputting to sub-query defined next*/
		$stmtdeleteTestQuesid = $sql->update('assigned_questions')
			                        ->set(array('status' => $status))
			                        ->where(array('test_id' => $test_id,'ques_id' => $quesId));
		$deleteQuesIds = $sql->prepareStatementForSqlObject ( $stmtdeleteTestQuesid );
		$result = $this->resultSetPrototype->initialize ( $deleteQuesIds->execute () );
		return $result;
	}
	
	/*multi-delete questions from test*/
	public function multideleteTestQues($quesId)
	{
		$session = new \Zend\Session\Container('test');
		$test_id = $session->testId;
		$status = 1;
		$sql = new Sql ( $this->adapter );
		$where = new Where();
		
		foreach ($quesId as $delQues){
		/*select question id's for selected test id,for inputting to sub-query defined next*/
		$stmtdeleteTestQuesid = $sql->update('assigned_questions')
		->set(array('status' => $status))
		->where(array('test_id' => $test_id,'ques_id' => $delQues));
		
		$deleteQuesIds = $sql->prepareStatementForSqlObject ( $stmtdeleteTestQuesid );
		$result = $this->resultSetPrototype->initialize ( $deleteQuesIds->execute () ); 
		}
		return $result;
	}
	
	/*get id of a test*/
	public function gettest($id) 
	{
		$id = ( int ) $id;
		$rowset = $this->tableGateway->select ( array (
				'id' => $id 
		) );
		$row = $rowset->current ();
		
		if (! $row) {
			throw new \Exception ( "Could not found row $id" );
		}
		return $row;
	}

	/*insert or update test details into database*/
	public function saveTest(Test $test) 
	{
		$data = array (
				'name' => $test->name,
				'description' => $test->description 
		);
		
		$id = ( int ) $test->id;
		if ($id == 0) {
			$this->tableGateway->insert ( $data );
		} else {
			if ($this->getTest ( $id )) {
				$this->tableGateway->update ( $data, array (
						'id' => $id 
				) );
			} else {
				throw new \Exception ( 'name id doesnt exists' );
			}
		}
	}

	/*delete a selected test*/
	public function deleteTest($id) 
	{
		$this->tableGateway->delete ( array (
				'id' => $id 
		) );
	}
}