<?php
/*
*@author : Ashwani Singh
*@date : 30-09-2013
*
*/

namespace Question\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoryTable
{
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway)  
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select(array('status'=>'0'));
		
		return $resultSet;
	}
	
	public function getCategory($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		
		if (!$row) {
			throw new \Exception("Could not found row $id");
		}
		return $row;
	}

	public function getCategoryId($name)
	{
		
		$rowset = $this->tableGateway->select(array('name' => $name));
		$row = $rowset->current();
	
		if (!$row) {
			throw new \Exception("Could not found row $id");
		}
		return $row->id;
	}
	public function getCategoryForCsv($name)
	{
	$rowset = $this->tableGateway->select(array('name' => $name , 'status' => 0));

		$row = $rowset->current();
		
		if (!$row) {
			return 1;	
			}
			else{
		return 0;}
	}
	
	public function saveCategory(Category $category)
	{
		$date = date('Y-m-d H:i:s');
		//$zero = 0;
			
		$data = array(
				'id' => $category->id,
				'name' => $category->name,
				'created_by'=>$category->created_by,
				'created_on'=>$date,
				'updated_by'=>$category->updated_by,
				'updated_on'=>$date,
				'status'=>'0',
	
		);
	
		$id = (int) $category->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$category_id= $this->tableGateway->getLastInsertValue();
			echo $category_id;
			return $category_id;
		} else {
			if ($this->getCategory($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Category id doesnt exists');
			}
		}
	}
	
	
}