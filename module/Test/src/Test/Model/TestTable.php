<?php
/*
* @author : Leena,Vijay
* @desc : Test Entity for Test module
* @created on : 30-06-2014
* ---------------------------------------------
* @modified on :
*
*
*
*/

namespace Test\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use User\Model\UserTable;
use Zend\Db\Sql\Where;

class TestTable
{
	protected $tableGateway;
	protected $resultSetPrototype;
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
		$this->resultSetPrototype = new ResultSet ();
	}

	/*get all active test from database*/
	public function fetchAll() 
	{
		/* $resultSet = $this->tableGateway->select ();
		return $resultSet;  */
	    $status = 0;
		$sql = new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
					  ->from ( array ('testTabl' => 'test' ) )
					  ->columns ( array ('id' => 'id',
										 'name' => 'name',
										 'description' => 'description',
					  					 'created_on' => 'created_on',
					  		             'updated' => 'updated_by',
					  					 'updated_on' => 'updated_on',
										 'status' => 'status' ) )
					  ->join ( array ('usersTabl' => 'users' ), 'created_by = usersTabl.id',
							   array ('created_by' => new \Zend\Db\Sql\Expression(
        			   				  "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)"),
							   		  'updated_by' => new \Zend\Db\Sql\Expression(
							          "CONCAT(usersTabl.first_name,' ',usersTabl.last_name)")))
					  /* ->join ( array ('userTabl' => 'users' ), 'updated_by = userTabl.testcube_id',
							   array ('updated_by' => new \Zend\Db\Sql\Expression(
        							"CONCAT(userTabl.first_name,' ',userTabl.last_name)"))) */
					  /*->join ( array ('categoryTabl' => 'category' ), 'cat_id = categoryTabl.id',
							   array ('categoryDesc' => 'name' ) )*/
					  ->where ( array('testTabl.status'=>$status) )
					  ->order('created_on DESC');
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () ); 
		return $result; 
	}

	/*get id of a test*/
	public function getTest($id) 
	{
		$id = ( int ) $id;
		$rowSet = $this->tableGateway->select ( array (
				'id' => $id 
		) );
		$row = $rowSet->current ();
		
		if (! $row) {
			throw new \Exception ( "Could not found row $id" );
		}
		return $row;
	}

	/*insert or update a test into database*/
	public function saveTest(Test $test,$userid) 
	{
		$date = date('Y-m-d H:i:s');
		$zero = 0;
		$id = ( int ) $test->id;
		if ($id == 0) {
			$data = array (
					'id' => $test->id,
					'name' => $test->name,
					'description' => $test->description,
					'created_by' => $userid,
					'created_on' => $date,
					'status' => $zero
			);
			$this->tableGateway->insert ( $data );
		} else {
			if ($this->getTest ( $id )) {
				$data = array (
						'id' => $test->id,
						'name' => $test->name,
						'description' => $test->description,
						'updated_by' => $userid,
						'updated_on' => $date,
						'status' => $zero
				);
				$this->tableGateway->update ( $data, array (
						'id' => $id 
				) );
			} else {
				throw new \Exception ( 'name id doesnt exists' );
			}
		}
	}
	
	/*inactivate a selected test*/
	public function deleteTest($id,$deletedBy)
	{
		$status = 1;
		$data = array (
				'updated_by' => $deletedBy,
				'status' => $status
		);
		$this->tableGateway->update ( $data, array (
				'id' => $id 
		) );
	}
	
	/*inactivate all selected test*/
	public function mulDeleteTest($testId,$deletedBy)
	{
		$status = 1;
		$data = array (
				'updated_by' => $deletedBy,
				'status' => $status
		);
		foreach ($testId as $key => $value) {
	
			$this->tableGateway->update ( $data, array (
					'id' => $value
			) );
		}
	}
	
	public function checkVal($chkId,$txtVal)
	{
		$zero = 0;
		$where = new Where();
		//$where(array('name'=>$txtVal,'status'=>$zero));
		if($chkId){
			$where->equalTo('name',$txtVal)->equalTo('status', $zero)->notEqualTo('id',$chkId);
		}else{
			$where->equalTo('name',$txtVal)->equalTo('status', $zero);
		}
		
		$sql = new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
					  ->from ( array ('testTabl' => 'test' ) )
					  ->columns ( array ('id' => 'id','name' => 'name',) )
					  ->where($where);
		
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray();
		
		if (empty($result)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getTests()
	{
		$testNames = $this->tableGateway->select ();
		
		$names = array();
		$i = 0;
		foreach ($testNames as $testName) {
			$names[$i]['name'] = $testName->name;
			$names[$i]['id'] = $testName->id;
			$i++;
		}
		
		if (empty( $names )) {
			throw new \Exception ( "Could not found row $id" );
		}
		return $names;
	}
}