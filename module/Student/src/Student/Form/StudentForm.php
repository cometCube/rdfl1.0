<?php
/**
 *@author : Ritika Davial
*@date : 30-06-2014
*@desc : Student Login Form class
*/

namespace Student\Form;

use Zend\Form\Form;



class StudentForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('student');
		$this->url = $name;

		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));

		$this->add(array(
				'name' => 'firstname',
				'type' => 'Text',
				'options' => array(
						'label' => 'First Name',
				),
				'attributes' => array(
						'placeholder' => 'First Name',
				)
		));

		$this->add(array(
				'name' => 'lastname',
				'type' => 'Text',
				'options' => array(
						'label' => 'Last Name',
				),
				'attributes' => array(
						'placeholder' => 'Last Name',
				)
		));
		
		$this->add(array(
				'name' => 'email',
				'type' => 'Text',
				'options' => array(
						'label' => 'Email',
				),
				'attributes' => array(
						'placeholder' => 'Email Address',
				)
		));

		$this->add(array(
				'name' => 'pass',
				'type' => 'Password',
				'options' => array(
						'label' => 'Password',
				),
				'attributes' => array(
						'placeholder' => 'Password',
				)
		));

		$this->add(array(
				'name' => 'login',
				'type' => 'submit',
				'attributes' => array(
						'value' => 'Take me In',
						'id'    => 'loginbtn',
				)
		));
	}
}