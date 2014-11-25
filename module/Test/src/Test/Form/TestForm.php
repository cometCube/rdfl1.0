<?php
/*
* @author : Leena,Vijay
* @desc : Test Form for Test module
* @created on : 30-06-2014
* ---------------------------------------------
* @modified on :
*
*
*
*/

namespace Test\Form;

use Zend\Form\Form;

class TestForm extends Form 
{
	public function __construct($name = null) 
	{
		parent::__construct ( 'test' );
		$this->url = $name;
		
		$this->add ( array (
				'name' => 'id',
				'type' => 'Hidden' 
		) );
		
		$this->add ( array (
				'name' => 'name',
				'type' => 'Text',
				'options' => array (
						//'label' => 'Test : ',
						'placeholder' => 'Type Test Name Here'
				),
				'attributes' => array(
						'id' => 'testName',
						'required' => 'required',
						'class'=>'form-control margin-left50 input-error',
						'maxlength' => '40',
						'placeholder' => 'Test Name Of Max 40 Characters'
				),
		) );
		
		$this->add ( array (
				'name' => 'description',
				'type' => 'Textarea',
				'class' => 'testdesc',
				'options' => array (
						//'label' => 'Description : ',
						'placeholder' => 'Type Test Description Here'
				),
				'attributes' => array(
						'id' => 'testDesc',
						'required' => 'required',
						'class'=>'testdesc',
						'maxlength' => '200'
				),
		) );
		
		$this->add ( array (
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array (
						'value' => 'Go',
						'id' => 'submitbutton',
						'class' => 'btn margin-left50 margin-top10 input-error'
				) 
		) );
	}
}