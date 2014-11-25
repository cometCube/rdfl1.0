<?php
/**
 *@author : Ashwani Singh
 *@date : 01-10-2013
 *@desc : Album Form class
 */

namespace Question\Form;

use Zend\Form\Form;



class AnswerForm extends Form 
{
	public function __construct($name = null)
	{
		parent::__construct('answer');
		$this->url = $name;

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',	
		));

		$this->add(array(
				'name' => 'question_id',
				'type' => 'Hidden',
		));
		
		

		$this->add(array(
				'name' => 'correct',
				'type' => 'Hidden',
		));

	}



}