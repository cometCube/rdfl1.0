<?php


namespace Test\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Console\Prompt\Select;
use Zend\Db\Adapter\Adapter;

class LinkAssignDatesTable
{
	protected $tableGateway;
	protected $adapter;
	
	public function __construct(TableGateway $tableGateway)  
	{
		$this->tableGateway = $tableGateway;
		$this->adapter = $this->tableGateway->getAdapter();
	}
	
	
	public function getquestion($id)
	{
		$test_id = (int) $id;
		$rowSet = $this->tableGateway->select(array('test_id' => $test_id));
		if (!$row) {
			throw new \Exception("Could not found row $id");
		}
		return $row;
	}
	
	public function saveLinkAssignDatesAndEmails($linkAssignDates)
	{
		$emails = explode(',',$linkAssignDates['emails']); 
		

		$linkAssignDatesData = array(
            'link_id'    => $linkAssignDates['linkId'],
            'link_code'  => $linkAssignDates['linkCode'],
            'showfrom'   => $linkAssignDates['showFrom'],
            'showuntill' => $linkAssignDates['showUntill'],
        );

        //insertion into the table
        $sql = new Sql($this->adapter);
        $insert = $sql->insert('link_assign_dates');
        $insert->values($linkAssignDatesData);
        $selectString = $sql->getSqlStringForSqlObject($insert);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $lastId = $this->adapter->getDriver()->getLastGeneratedValue();

        if(!empty($results)) {
        	foreach ($emails as $mail) {
            	$sql = new Sql($this->adapter);
            	$insert = $sql->insert('link_assign_emails');
            	$insert->values(array(
            		'linkassigndate_id'=>$lastId,
            		'test_taker_email' =>$mail
            		));
            	$selectString = $sql->getSqlStringForSqlObject($insert);
            	$results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
            }
            if(!empty($results)) {
            	return true;
            }
            else {
            	return false;
            }

        } else {
        	return false;
        }
	}

	public function fetchLinkAssignDatesDetails($linkId)
	{
		$linkId = (int) $linkId;
		$rowSet = $this->tableGateway->select(array('link_id' => $linkId));
		if (!$rowSet) {
			throw new \Exception("Could not found row $id");
		}
		return $rowSet;
	}
}