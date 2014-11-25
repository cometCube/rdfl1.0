<?php

namespace Result\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\ResultSet\ResultSet;

class ResultTable
{
	protected $tableGateway;
	public $resultSetPrototype;
	
	public function __construct(TableGateway $tableGateway)  
	{
		$this->tableGateway = $tableGateway;
		$this->resultSetPrototype = new ResultSet ();
	}
	public function fetch_result()
	{
		$where = new Where ();
		$sql =new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
		              ->from ( array ('t' => 'test' ) )
		              ->columns ( array ('testname' => 'name') )
					  ->join ( array ('l' => 'link'), 't.id = l.test_id',
					  		  array('linkName'=>'name'))
					  ->join ( array ('lad' => 'link_assign_dates') , 'lad.link_id = l.id' ,array('linkCode'=>'link_code'))
					  ->join ( array ('r' => 'result') , ' lad.id = r.link_assign_dates_id',Select::SQL_STAR , select::JOIN_RIGHT)
					  ->join ( array ('s' => 'student' ), 's.id = r.student_id'
					  	,array('stuFname'=>'fname','stuLname'=>'lname','stuEmail'=>'email'))
					  ->where(array('r.status' => '0'));

		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();

		return $result;
	}
	
	public function get_result($id)
	{
		if(!$id){
			
		}
		$sql =new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
		->from ( array ('t' => 'test' ) )
		->columns ( array ('testname' => 'name') )
		->join ( array ('l' => 'link'), 't.id = l.test_id',
				array('linkName'=>'name'))
				->join ( array ('lad' => 'link_assign_dates') , 'lad.link_id = l.id' ,array('linkCode'=>'link_code'))
				->join ( array ('r' => 'result') , ' lad.id = r.link_assign_dates_id',Select::SQL_STAR , select::JOIN_RIGHT)
				->join ( array ('s' => 'student' ), 's.id = r.student_id'
						,array('stuFname'=>'fname','stuLname'=>'lname','stuEmail'=>'email'))
				->where(array('r.id'=>$id));
		
				$statement = $sql->prepareStatementForSqlObject ( $select );
				$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
	
				return $result;
	}
	
	public function fetch_tests()
	{
		$sql = new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
		->from ( 'test')
		->columns ( array ('testname' => 'name') )
		->where (array('status'=> 0));
		
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
		
		return $result;
	}
	
	public function fetch_tests_by_testid($testname)
	{
		$sql = new Sql ( $this->tableGateway->getAdapter() );
		$select = $sql->select ()
		->from ( 'test')
		->columns ( array ('testname' => 'name') )
		->where (array('status'=> 0,'name'=>$testname));
	
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$result = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
	
		return $result;
	}
	
	/*inactivate(delete) a selected result row*/
	public function deleteResult($id)
	{
		$status = 1;
		$data = array (
				'status' => $status
		);
		$this->tableGateway->update ( $data, array (
				'id' => $id
		));
	}
	
	public function deleteallResult($id) {
		
		$status = 1;
		$data = array (
				'status' => $status
		);
	
		//$id = explode ( ",", $id );
		
		
	
		$n = count($id);
		for($i = 0; $i < $n; $i ++){
			$this->tableGateway->update ( $data, array (
				'id' => $id[$i]
		));
		}
	}

}