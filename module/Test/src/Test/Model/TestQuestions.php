<?php
/*
 * @author : Leena,Vijay
* @desc : Test Questions Entity for Test module
* @created on : 01-07-2014
* ---------------------------------------------
* @modified on :
*
*
*
*/

namespace Test\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class TestQuestions implements InputFilterAwareInterface 
{
	public $id;
	public $test_id;
	public $ques_id;
	public $cat_id;
	public $description;
	public $status;
	
	protected $inputFilter;

	public function exchangeArray($data) 
	{
		$this->id = (! empty ( $data ['id'] )) ? $data ['id'] : null;
		$this->test_id = (! empty ( $data ['test_id'] )) ? $data ['test_id'] : null;
		$this->ques_id = (! empty ( $data ['ques_id'] )) ? $data ['ques_id'] : null;
		$this->cat_id = (! empty ( $data ['cat_id'] )) ? $data ['cat_id'] : null;
		$this->description = (! empty ( $data ['description'] )) ? $data ['description'] : null;
		$this->status = (! empty ( $data ['status'] )) ? $data ['status'] : null;
	}

	public function getArrayCopy() 
	{
		return get_object_vars ( $this );
	}

	public function setInputFilter(InputFilterInterface $inputFilter) 
	{
		throw new \Exception ( "Not used" );
	}

	public function getInputFilter() 
	{
		if (! $this->inputFilter) {
			$inputFilter = new InputFilter ();
			
			$inputFilter->add ( array (
					'name' => 'id',
					'required' => true,
					'filters' => array (
							array (
									'name' => 'Int' 
							) 
					) 
			) );
			
			$inputFilter->add ( array (
					'name' => 'name',
					'required' => true,
					'filters' => array (
							array (
									'name' => 'StripTags' 
							),
							array (
									'name' => 'StringTrim' 
							) 
					),
					'validators' => array (
							array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 1,
											'max' => 100 
									) 
							) 
					) 
			) );
			
			$inputFilter->add ( array (
					'name' => 'description',
					'required' => true,
					'filters' => array (
							array (
									'name' => 'StripTags' 
							),
							array (
									'name' => 'StringTrim' 
							) 
					),
					'validators' => array (
							array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 1,
											'max' => 100 
									) 
							) 
					) 
			) );
			
			$this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
