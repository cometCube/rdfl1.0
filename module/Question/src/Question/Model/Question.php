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


class Question implements InputFilterAwareInterface
{
	public $id;
	public $cat_id;
	public $created_by;
	public $updated_by;
	public $updated_on;
	public $created_on;
	public $description;
	public $status;
	public $points;
	public $type;
	

    protected $inputFilter;
	
	public function exchangeArray($data)
	{
		
		//var_dump($data);
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->cat_id = (!empty($data['cat_id'])) ? $data['cat_id'] : null;
		$this->created_by = (!empty($data['created_by'])) ? $data['created_by'] : null;
		$this->updated_by = (!empty($data['updated_by'])) ? $data['updated_by'] : null;
		$this->created_on = (!empty($data['created_on'])) ? $data['created_on'] : null;
		$this->updated_on = (!empty($data['updated_on'])) ? $data['updated_on'] : null;
		$this->description = (!empty($data['description'])) ? $data['description'] : null;
		$this->status = (isset($data['status'])) ? $data['status'] : null;
		$this->points = (!empty($data['points'])) ? $data['points'] : null;
		$this->type = (isset($data['type'])) ? $data['type'] : null;
	
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
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            
            $inputFilter->add(array(
            		'name' => 'cat_id',
            		'required' => false,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            $inputFilter->add(array(
            		'name' => 'created_by',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            $inputFilter->add(array(
            		'name' => 'updated_by',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            
            $inputFilter->add(array(
            		'name' => 'created_on',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            $inputFilter->add(array(
            		'name' => 'updated_on',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
            
            $inputFilter->add(array(
            		'name' => 'description',
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
            
            
            $inputFilter->add(array(
            		'name' => 'points',
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
            
            $inputFilter->add(array(
            		'name' => 'status',
            		'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),
            ));
            
           
      
            $inputFilter->add(array(
            		'name' => 'type',
            		'required' => true,
            		'filters' => array(
            			
            		),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}