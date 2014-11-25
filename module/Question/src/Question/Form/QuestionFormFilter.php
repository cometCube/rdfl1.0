<?php
/**
 *@author : Ashwani Singh
 *@date : 01-10-2013
 *@desc : Album Form class
 */

namespace Question\Form;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\InputFilter\InputFilter;


class QuestionFormFilter extends InputFilter
{
	public function __construct()
	{
        $this->add(array(
			'name' => 'id',
			'required' => true,
			'filters' => array(
				array('name' => 'Int'),
			),
		));
        
        
        $this->add(array(
        		'name' => 'cat_id',
        		'required' => false,
        		'filters' => array(
        				array('name' => 'Int'),
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
        		'name' => 'description',
        		'required' => true,
                'noSpace'=> true,
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
        		'name' => 'points',
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
        
        $this->add(array(
        		'name' => 'type',
        		'required' => false,
        		'filters' => array(
        				array('name' => 'Int'),
        		),
        ));
        
 
	
    }
}