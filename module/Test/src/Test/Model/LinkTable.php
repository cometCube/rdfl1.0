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

class LinkTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() 
    {
    	$status = 0;
        $resultSet = $this->tableGateway->select (array('status' => $status));
        return $resultSet;
    }

    public function fetchLinkDetailsByTestId($testId) 
    {
        $testId = ( int ) $testId;
        $status = 0;
        $rowSet = $this->tableGateway->select ( array (
            'test_id' => $testId,
        	'status' => $status
        ) );

        if (! $rowSet) {
            throw new \Exception ( "Could not found row $testId" );
        }
        return $rowSet;
    }

    public function getLink($id) 
    {
        $id = ( int ) $id;
        $status = 0;
        $rowSet = $this->tableGateway->select ( array (
            'id' => $id,
        	'status' => $status
        ) );
        $row = $rowSet->current ();

        if (! $row) {
            throw new \Exception ( "Could not found row $id" );
        }
        return $row;
    }
    
    public function saveLink(Test $link) 
    {
        $data = array (
            'name' => $link->name,
            'test_id' => $link->test_id,
        );

        $id = ( int ) $link->id;
        if ($id == 0) {
            $this->tableGateway->insert ( $data );
        } else {
            if ($this->getLink ( $id )) {
                $this->tableGateway->update ( $data, array (
                    'id' => $id
                ) );
            } else {
                throw new \Exception ( 'name id doesnt exists' );
            }
        }
    }

    public function deleteLink($id,$deletedBy) 
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
    
    /*inactivate a selected test*/
    public function deleteTestLink($id,$deletedBy)
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
}