<?php


namespace Test\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Settings implements InputFilterAwareInterface
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
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->name = (!empty($data['name'])) ? $data['name'] : null;
		$this->description = (!empty($data['description'])) ? $data['description'] : null;
		
		$this->created_by = (!empty($data['created_by'])) ? $data['created_by'] : null;
		
		$this->created_on = (!empty($data['created_on'])) ? $data['created_on'] : null;
		
		$this->updated_by = (!empty($data['updated_by'])) ? $data['updated_by'] : null;
		
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

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
          

            $inputFilter->add(array(
                'name'     => 'name',
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
            		'name'     => 'description',
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
            								'min'      => 1,
            								'max'      => 100,
            						),
            				),
            		),
            ));
            

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}