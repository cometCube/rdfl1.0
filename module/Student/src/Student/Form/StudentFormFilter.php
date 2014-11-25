<?php
/**
 *@author : Ritika Davial
*@date : 30-06-2014
*@desc : Student Login Form class
*/

namespace Student\Form;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\InputFilter\InputFilter;


class StudentFormFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name' => 'id',
				'required' => false,
				'filters' => array(
						array('name' => 'Int'),
				),
		));

		$this->add(array(
				'name' => 'firstname',
				'required' => true,
				'filters' => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
				),
				'validators' => array(
						array(
								'name' => 'StringLength',
								'options' => array(
										'encoding' => 'UTF-8',
										'min' => 4,
										'max' =>30,
								),
						),
				),
		));
		
		$this->add(array(
				'name' => 'lastname',
				'required' => true,
				'filters' => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
				),
				'validators' => array(
						array(
								'name' => 'StringLength',
								'options' => array(
										'encoding' => 'UTF-8',
										'min' => 4,
										'max' =>30,
								),
						),
				),
		));
		
		

		$this->add(array(
				'name' => 'email',
				'required' => true,
				'filters' => array(
						array('name' => 'StripTags')
				),
				'validators' => array(
						array(
							'name'    => 'StringLength',
							'options' => array(
										'encoding' => 'UTF-8',
										'min' => 4,
										'max' =>30,
							),
						new \Zend\Validator\EmailAddress()
						),
				),
		));

	}
}