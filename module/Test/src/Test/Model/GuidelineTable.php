<?php


namespace Test\Model;

use Zend\Db\TableGateway\TableGateway;

class SettingsTable
{
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway)  
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
}