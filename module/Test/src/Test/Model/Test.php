<?php
/*
* @author : Leena,Vijay
* @desc : Test Entity for Test module
* @created on : 30-06-2014
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

class Test implements InputFilterAwareInterface 
{
	public $id;
	public $name;
	public $description;
	public $created_by;
	public $created_on;
	public $updated_by;
	public $updated_on;
	public $status;

	protected $inputFilter;

	public function exchangeArray($data) 
	{
		$this->id = (! empty ( $data ['id'] )) ? $data ['id'] : null;
		$this->name = (! empty ( $data ['name'] )) ? $data ['name'] : null;
		$this->description = (! empty ( $data ['description'] )) ? $data ['description'] : null;
		
		$this->created_by = (! empty ( $data ['created_by'] )) ? $data ['created_by'] : null;
		
		$this->created_on = (! empty ( $data ['created_on'] )) ? $data ['created_on'] : null;
		
		$this->updated_by = (! empty ( $data ['updated_by'] )) ? $data ['updated_by'] : null;
		
		$this->updated_on = (! empty ( $data ['updated_on'] )) ? $data ['updated_on'] : null;
		$this->status = (! empty ( $data ['status'] )) ? $data ['status'] : null;
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
									'name' => 'Zend\Filter\StripTags' 
							),
							array (
									'name' => 'Zend\Filter\StringTrim' 
							),
							array(
									'name' => 'Zend\Filter\Null'
							),
							array(
									'name' => 'Zend\Filter\HtmlEntities'
							)
					),
					'validators' => array (
									array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 2,
											'max' => 40,
											'messages' => array (
                        										 \Zend\Validator\StringLength::TOO_SHORT => 
																 'Test Name \'%value%\' is too short',
                        										 \Zend\Validator\StringLength::TOO_LONG => 
																 'Test Name \'%value%\' is too long'
                        										),
										    ) 
									),
									
									    
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
							),
							array(
									'name' => 'Zend\Filter\Null'
							),
							array(
									'name' => 'Zend\Filter\HtmlEntities'
							)
					),
					'validators' => array (
							array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 2,
											'max' => 200,
											'messages' => array (
                        										 \Zend\Validator\StringLength::TOO_SHORT => 
																 'Test Description \'%value%\' is too short',
                        										 \Zend\Validator\StringLength::TOO_LONG => 
																 'Test Description \'%value%\' is too long'
                        										),
										    )
									),
							
							) 
					) 
			) ;
			
			$this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
