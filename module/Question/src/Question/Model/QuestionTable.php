<?php
/*
*@author : Jyoti Kumari
*@date :
*
*/

namespace Question\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class QuestionTable
{
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway)  
	{
		$this->tableGateway = $tableGateway;
		$this->resultSetPrototype = new ResultSet ();
	}
	
	public function fetchAll()
	{
		/* $resultSet = $this->tableGateway->select(array('status'=>'0')); */
		
		$status = 0;
		$sql = new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
					  ->from ( array ('questionTabl' => 'questions' ) )
					  ->join ( array ('usersTabl' => 'users' ), 'created_by = usersTabl.id',
							   array ('createdBy' => new \Zend\Db\Sql\Expression(
									  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
									  'updatedBy' => new \Zend\Db\Sql\Expression(
									  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
					  ->join(array('categoryTabl' => 'category'),'cat_id = categoryTabl.id',
					  		 array('catName' => 'name'))
					  ->where ( array('questionTabl.status'=>$status) )
					  ->order('questionTabl.created_on DESC');
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$resultSet = $this->resultSetPrototype->initialize ( $statement->execute () );
		return $resultSet;
	}
	
	public function getQuestion($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		
		if (!$row) {
			throw new \Exception("Could not found row $id");
		}
		return $row;
	}
	
	public function getQuestionbycatid($id)
	{
		
		
		$sql = new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select()
		->from(array('questionTabl'=>'questions'))
		->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
				array('catName'=>'name'))
		->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
						array ('createdBy' => new \Zend\Db\Sql\Expression(
								"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
								'updatedBy' => new \Zend\Db\Sql\Expression(
										"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
		->where(array('questionTabl.status'=>'0',
						'questionTabl.cat_id'=>$id))
		->order('created_on DESC');
		
		$statement = $sql->prepareStatementForSqlObject ( $select );
					
		$question = $this->resultSetPrototype->initialize ( $statement->execute () );
		return $question;
		
	}
	
	public function getCatQuestions($id)
	{
		if($id == 0 )
		{
		
			$sql = new Sql ( $this->tableGateway->getAdapter() );
			$select = $sql->select()
			->from(array('questionTabl'=>'questions'))
			->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
					array('catName'=>'name'))
			->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
					 array ('createdBy' => new \Zend\Db\Sql\Expression(
							 "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
							'updatedBy' => new \Zend\Db\Sql\Expression(
							"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
			->where(array('questionTabl.status'=>'0',))
			->order('created_on DESC');
				  
			$statement = $sql->prepareStatementForSqlObject ( $select );
			
			$question = $this->resultSetPrototype->initialize ( $statement->execute () );
		}
		else 
		{
			$sql = new Sql ( $this->tableGateway->getAdapter() );
			$select = $sql->select()
						  ->from(array('questionTabl'=>'questions'))
						  ->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
	    				  		 array('catName'=>'name'))
	    				  ->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
	    				  		   array ('createdBy' => new \Zend\Db\Sql\Expression(
	    				  		 		  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
	    				  		 		  'updatedBy' => new \Zend\Db\Sql\Expression(
	    				  		 		  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
	    		   		  ->where(array('questionTabl.cat_id' => $id,
	    						 		'questionTabl.status'=>'0',))
	    				  ->order('created_on DESC');
	    
	    	$statement = $sql->prepareStatementForSqlObject ( $select );
	    	
	    	$question = $this->resultSetPrototype->initialize ( $statement->execute () );
	    	
	    	/* $question = $this->tableGateway->select($select); */
	    	
		}
    	return $question;
	}
	
	public function getTypeQuestions($id)
	{
		if($id == 2 )
		{
			$sql = new Sql ( $this->tableGateway->getAdapter() );
			$select = $sql->select()
			->from(array('questionTabl'=>'questions'))
			->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
					array('catName'=>'name'))
			->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
					 array ('createdBy' => new \Zend\Db\Sql\Expression(
							 "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
							'updatedBy' => new \Zend\Db\Sql\Expression(
							"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
			->where(array('questionTabl.status'=>'0',))
			->order('created_on DESC');
				  
			$statement = $sql->prepareStatementForSqlObject ( $select );
			
			$question = $this->resultSetPrototype->initialize ( $statement->execute () );
		}else 
		{
			
			$sql = new Sql ( $this->tableGateway->getAdapter() );
			$select = $sql->select()
			->from(array('questionTabl'=>'questions'))
			->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
					array('catName'=>'name'))
			->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
							array ('createdBy' => new \Zend\Db\Sql\Expression(
									"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
									'updatedBy' => new \Zend\Db\Sql\Expression(
											"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
			->where(array('questionTabl.type' => $id,
						  'questionTabl.status'=>'0',))
			->order('created_on DESC');
											 
			$statement = $sql->prepareStatementForSqlObject ( $select );
			
			$question = $this->resultSetPrototype->initialize ( $statement->execute () );
	}
		return $question;
	}
	

public function getTypeAndCategoryQuestions($filtertype)
{
$questiontype=$filtertype['questiontype'];
$categoryid= $filtertype['categorytype'];
if($questiontype=='2')
{
	$sql = new Sql ( $this->tableGateway->getAdapter() );
			$select = $sql->select()
			->from(array('questionTabl'=>'questions'))
			->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
					array('catName'=>'name'))
			->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
					 array ('createdBy' => new \Zend\Db\Sql\Expression(
							 "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
							'updatedBy' => new \Zend\Db\Sql\Expression(
							"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
			->where(array('questionTabl.status'=>'0',
							'questionTabl.cat_id' => $categoryid,
							))
			->order('created_on DESC');
				  
			$statement = $sql->prepareStatementForSqlObject ( $select );
			
			$question = $this->resultSetPrototype->initialize ( $statement->execute () );
			
}
else if($categoryid=='0')
{
	
	$sql = new Sql ( $this->tableGateway->getAdapter() );
	$select = $sql->select()
	->from(array('questionTabl'=>'questions'))
	->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
			array('catName'=>'name'))
	->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
			array ('createdBy' => new \Zend\Db\Sql\Expression(
							"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
							'updatedBy' => new \Zend\Db\Sql\Expression(
							"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
	->where(array('questionTabl.status'=>'0',
				'questionTabl.type' => $questiontype,
				))
	->order('created_on DESC');
	
	$statement = $sql->prepareStatementForSqlObject ( $select );
										
	$question = $this->resultSetPrototype->initialize ( $statement->execute () );
}else{
	$sql = new Sql ( $this->tableGateway->getAdapter() );
	$select = $sql->select()
				->from(array('questionTabl'=>'questions'))
				->join(array('categoryTabl'=>'category'),'questionTabl.cat_id=categoryTabl.id',
						array('catName'=>'name'))
				->join ( array ('usersTabl' => 'users' ), 'questionTabl.created_by = usersTabl.id',
						array ('createdBy' => new \Zend\Db\Sql\Expression(
							"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
							'updatedBy' => new \Zend\Db\Sql\Expression(
									"CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
				->where(array('questionTabl.type' => $questiontype,
							  'questionTabl.cat_id' => $categoryid,
							  'questionTabl.status'=>'0',))
				->order('created_on DESC');
	
	$statement = $sql->prepareStatementForSqlObject ( $select );
										
	$question = $this->resultSetPrototype->initialize ( $statement->execute () );


}
return $question;
}
public function saveQuestion(Question $question,$userid)
	{
		
		$date = date('Y-m-d H:i:s');
        
		$id = (int) $question->id;
		if ($id == 0) {
			
			$data = array(
					'cat_id' => $question->cat_id,
					'created_by' => $userid,
					'created_on' => $date,
					'description' => $question->description,
					'status' => $question->status,
					'type' => $question->type,
					'points' => $question->points,
			);
			
			$this->tableGateway->insert($data);
			$question_id= $this->tableGateway->getLastInsertValue();
			return $question_id;
		} else {
			if ($this->getQuestion($id)) {
				
				$data = array(
						'cat_id' => $question->cat_id,
						'updated_by' => $userid,
						'updated_on' => $date,
						'description' => $question->description,
						'status' => $question->status,
						'type' => $question->type,
						'points' => $question->points,
				);
				
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Question id doesnt exists');
			}
		}
	}
	
	
	//soft delete
	
// 	public function deleteMultipleQuestion($id)
// 	{
// 		$ids = array();
// 		$i = 0;
// 		foreach($id as $k=>$v)
// 		{
// 			$ids[$i++] = $k;
// 		}
// 		//$this->tableGateway->delete(array('id' => $ids));
// 		$this->tableGateway->update(array('status' => '1'), array('id'=>$ids));
// 	}
	public function deleteQuestion($id)
	{
		
		$this->tableGateway->update(array('status' => '1'), array('id'=>$id));
				
	}

	
public function deleteallQuestion($questionid) {
	
	                $quesnid = explode(",", $questionid);
	
        foreach ($quesnid as $key => $value) {
       
            		$this->tableGateway->update(array('status' => '1'), array('id'=>$value));

       
           
        }
    }



public function deleteQuestionUsingCatId($id,$deletedBy) {
	
		$one = 1;
		$data = array (
				'updated_by' => $deletedBy,
				'status' => $one 
		);
		$this->tableGateway->update(array('status' => '1'), array('cat_id'=>$id));
	}

public function deleteAllQuestionUsingCatId($id,$deletedBy) {
	
		$one = 1;
		$data = array (
				'updated_by' => $deletedBy,
				'status' => $one 
		);
		
		$id = explode ( ",", $id );
		// print_r($id);
		
		$n = sizeof ( $id );
		// print_r($id);
		for($i = 0; $i < $n; $i ++)
			/*$this->tableGateway->update ( $data, array (
					'id' => $id 
			) );*/
$this->tableGateway->update(array('status' => '1'), array('cat_id'=>$id));
	}


}