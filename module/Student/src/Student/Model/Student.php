<?php

namespace Student\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Student\Form\StudentForm;

class Student implements InputFilterAwareInterface
{
	public $id;
	public $firstname;
	public $lastname;
	public $email;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : " ";
		$this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
		$this->lastname  = (!empty($data['lastname'])) ? $data['lastname']: null;
		$this->email  = (!empty($data['email'])) ? $data['email']: null;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name' => 'id',
				'required' => false,
				'filters' => array(
						array('name' => 'Int'),
				),
		));

		$inputFilter->add(array(
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

			$inputFilter->add(array(
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

			
		
			 $inputFilter->add(array(
               'name'     => 'email',
               'required' => true,
               'filters'  => array(
                   array('name' => 'StripTags'),
                   array('name' => 'StringTrim'),
               ),

               'validators' => array(
                   array(
                       'name'    => 'StringLength',
                       'options' => array(
                           'encoding' => 'UTF-8',
                       ),
                   ),
                   array(
                       'name' => 'NotEmpty',
                       'options' => array(
                           'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => "Email is empty!",
                       ),
                     ),
                  ),
                  array(
                   'name' => 'Regex',
                   'options' => array(
                       'pattern' => "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/",
                       'messages' => array(
                           \Zend\Validator\Regex::INVALID => 'Invalid Email',
                           \Zend\Validator\Regex::NOT_MATCH => 'Invalid Email',
                       ),
                   ),
               ),
             ),
           ));
			
			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}