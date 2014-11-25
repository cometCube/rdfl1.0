<?php
/*
*@author : Ashwani Singh
*@date : 30-09-2013
*
*/

namespace Question\Model;

use Zend\Db\TableGateway\TableGateway;

class AnswerTable
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
	
public function getAnswer($id)
	{
		if($id == 0 )
		{
			$answers = $this->tableGateway->select();
		}
		else 
		{
			$select = $this->tableGateway->getSql()->select();
	    	$select->where(array(
	    			'question_id' => $id
	    	));
	    	$answers = $this->tableGateway->selectWith($select);
		}
    	return $answers;
	}
	
	
	public function getAnswersId($id)
	{
		if($id == 0 )
		{
			$answers = $this->tableGateway->select();
		}
		else
		{
		
			$select = $this->tableGateway->getSql()->select();
			//$select->columns(array('id'=>'id'));
			$select->where(array(
					'question_id' => $id
			));
			$answers = $this->tableGateway->selectWith($select);
			
			
		}
		return $answers;
	}
	
	public function saveAnswer(Answer $answer)
	{
		
		$data = array(
				'question_id' => $answer->question_id,
				'question_option' => $answer->question_option,
			    'correct' => $answer->correct,
				
		);
		$this->tableGateway->insert($data);
// 		$id = (int) $answer->id;
// 		if ($id == 0) {
// 			$this->tableGateway->insert($data);
// 		} else {
// 			if ($this->getAnswer($id)) {
// 				$this->tableGateway->update($data, array('id' => $id));
// 			} else {
// 				throw new \Exception('answer id doesnt exists');
// 			}
// 		}
	}
	public function saveMultipleEditAnswer(Answer $answer)
	{
		
		$data = array(
				'question_id' => $answer->question_id,
				'question_option' => $answer->question_option,
				'correct' => $answer->correct,
	
		);
		$qid = (int) $answer->question_id;
		$id = (int) $answer->id;
		unset($data->question_id);
	
		
		$this->tableGateway->update($data, array('question_id' => $qid, 'id' => $id));
		//$this->tableGateway->insert($data);
			
	}
	public function saveEditAnswer(Answer $answer)
	{
	
		$data = array(
				'question_id' => $answer->question_id,
				'question_option' => $answer->question_option,
				'correct' => $answer->correct,
	
		);
	
		//$id = (int) $answer->id;
	
		$this->tableGateway->insert($data);
			
	}
	
	public function deleteAnswer($id)
	{
		$this->tableGateway->delete(array('question_id'=>$id));
	
	}
	
	
	public function deleteAnswerId($id)
	{
		
	 		$ids = array();
	 		$i = 0;
	 		foreach($id as $k=>$v)
	 		{
	 			$ids[$i++] = $v;
	 		}
	 		
	 		$this->tableGateway->delete(array('id' => $ids));
	 	
	 	}
}