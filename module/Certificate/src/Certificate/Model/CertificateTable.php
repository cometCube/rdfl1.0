<?php
/*
 * @author : Divesh Pahuja @date : 03-07-2014
 */
namespace Certificate\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class CertificateTable 
{
	protected $tableGateway;
	protected $adapter;
	public $resultSetPrototype;
	
	public function __construct(TableGateway $tableGateway) 
	{
		$this->tableGateway = $tableGateway;
		$this->adapter = $this->tableGateway->getAdapter ();
		$this->resultSetPrototype = new ResultSet ();
	}
	
	public function fetchAll() 
	{
		$resultSet = $this->tableGateway->select ( function (Select $select) {
			$select->join ( 'users', 'certificate.created_by = users.id', array (
					'first_name',
					'last_name' 
			) );
			$select->where ( 'certificate.status = 0' );
		} );
		return $resultSet;
	}
	
	public function getCertificate($id) 
	{
		/*
		 * $id = (int) $id; $rowset = $this->tableGateway->select(array('id' => $id)); $row = $rowset->current(); if (!$row) { throw new \Exception("Could not found row $id"); } return $row;
		 */
	}
	
	public function saveCertificate(Certificate $certificate, $userid) 
	{
		$date = date ( 'Y-m-d H:i:s' );
		$zero = 0;
		
		$data = array (
					'title' => $certificate->title,
					'assigned_to' => $certificate->assigned_to,
					'email' => $certificate->email,
					'created_by' => $userid,
					'created_on' => $date,
					'status' => $zero 
		);
		$this->tableGateway->insert ( $data );
		$lastInsertId = $this->adapter->getDriver ()->getConnection ()->getLastGeneratedValue ();

		return $lastInsertId;
	}
	
	public function deleteCertificate($certificateId, $deletedBy) 
	{
		$status = 1;
		$data = array (
				'updated_by' => $deletedBy,
				'status' => $status 
		);
		$result = $this->tableGateway->update ( $data, array (
				'id' => $certificateId 
		) );
		
		$this->deleteFileFromdisk($certificateId);
	}
	
	public function multiDeleteCertificate($certificateIds, $deletedBy) 
	{
		$status = 1;
		$data = array (
				'updated_by' => $deletedBy,
				'status' => $status 
		);
		foreach ( $certificateIds as $key => $value ) {
			
			$this->tableGateway->update ( $data, array (
					'id' => (int) $value
			) );
			$this->deleteFileFromdisk((int) $value);
		}
	}
	
	// delete file form the hard disk....
	public function deleteFileFromdisk($certificateId)
	{
		$filePath = '/var/www/ZendTestcube/data/cache/';
		$fileName = 'certificate'.$certificateId.'.pdf';
		
		$old = getcwd(); // Save the current directory
		chdir($filePath);
		
		unlink($fileName);
		chdir($old); // Restore the old working directory
	}
	
	/* load all questions of a test */
	public function getAssignedTo($id)
	{
		$testId = ( int ) $id;
		$zero = 0;
	
		$sql = new Sql ( $this->adapter );
		$select = $sql->select ()
		->from ( array ('testTable' => 'test' ) )
		->columns ( array ('testId' => 'id', 'testName' => 'name' ) )
		->join ( array ('linkTable' => 'link' ), 'linkTable.test_id = testTable.id',
				array ('linkId' => 'id'))
 		->join ( array ('linkAssignDatesTable' => 'link_assign_dates' ), 'linkAssignDatesTable.link_id = linkTable.id',
 				array ('linkAssignDatesId' => 'id' ) )
 		->join ( array ('resultTable' => 'result' ), 'resultTable.link_assign_dates_id = linkAssignDatesTable.id',
 				array ('studentId' => 'student_id', 'percentage' => 'percentage', 'resultId' => 'id',
 						'startDate' => 'date_started' ) )
 		->join ( array ( 'studentTable' => 'student'), 'resultTable.student_id= studentTable.id',
 				array('firstName' => 'fname', 'lastName' => 'lname') )
 		->where( array ( 'testTable.id' => $testId, 'testTable.status' => $zero,
 						'linkTable.status' => $zero, 'resultTable.status' => $zero) );

		$statement = $sql->prepareStatementForSqlObject ( $select );
		$resultSet = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
		
		$arrData = array();
		$i = 0;
		foreach ($resultSet as $result) {
			$i = $result['resultId'];
			$arrData[$i]['name'] = $result['firstName']. " ".$result['lastName']; 
			$arrData[$i]['resultId'] = $result['resultId'];
			$arrData[$i]['percentage'] = $result['percentage'];
			$date = date_format(date_create($result['startDate']),'l jS \of F Y h:i:s A' );
			$arrData[$i]['startDate'] = $date;
			$arrData[$i]['testName'] = $result['testName'];
			$i++;
		}
		return $arrData;
	}
	
	public function getResultData($id)
	{
		$resultId = ( int ) $id;
		$zero = 0;
		
		$sql = new Sql ( $this->adapter );
		$select = $sql->select ()
		->from ( array ('resultTable' => 'result' ) )
		->columns ( array ('percentage' => 'percentage', 'startDate' => 'date_started' ) )
		->join ( array ('studentTable' => 'student' ), 'studentTable.id = resultTable.student_id',
				array ('name' => new \Zend\Db\Sql\Expression 
						("CONCAT(studentTable.fname,' ',studentTable.lname)")))
		->where( array ( 'resultTable.id' => $resultId));
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$resultSet = $this->resultSetPrototype->initialize ( $statement->execute () )->toArray ();
		return $resultSet;
	}
}