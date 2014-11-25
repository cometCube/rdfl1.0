<?php
/*
 *@author : Ashwani Singh 
 *@date : 30-09-2013 
 *-----------------------------------------
 *        modified
 *-----------------------------------------
 *
 */

namespace Question\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Category implements InputFilterAwareInterface
{
	public $id;
	public $name;
	public $created_by;
	public $updated_by;
	public $updated_on;
	public $created_on;
	
	public $status;
	
	

    protected $inputFilter;
	
	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->name = (!empty($data['name'])) ? $data['name'] : null;
		$this->created_by = (!empty($data['created_by'])) ? $data['created_by'] : null;
		$this->updated_by = (!empty($data['updated_by'])) ? $data['updated_by'] : null;
		$this->created_on = (!empty($data['created_on'])) ? $data['created_on'] : null;
		$this->updated_on = (!empty($data['updated_on'])) ? $data['updated_on'] : null;
		$this->status = (!empty($data['status'])) ? $data['status'] : null;
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
            $this->add(array(
            		'name' => 'id',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            
             $this->add(array(
            		'name' => 'name',
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
            								'min' => 1,
            								'max' =>100,
            						),
            				),
            		),
            ));
            
            $this->add(array(
            		'name' => 'created_by',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            $this->add(array(
            		'name' => 'updated_by',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            
            $this->add(array(
            		'name' => 'created_on',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            $this->add(array(
            		'name' => 'updated_on',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
           
            
            $this->add(array(
            		'name' => 'status',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
           
            
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}