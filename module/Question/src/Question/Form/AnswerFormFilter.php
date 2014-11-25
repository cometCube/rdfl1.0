<?php
/**
 *@author : Ashwani Singh
 *@date : 01-10-2013
 *@desc : Album Form class
 */

namespace Question\Form;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\InputFilter\InputFilter;


class AnswerFormFilter extends InputFilter
{
	public function __construct()
	{
        $this->add(array(
			'name' => 'id',
			/*'required' => true,
			'filters' => array(
				array('name' => 'Int'),
			),*/
		));
        
        
        $this->add(array(
        		'name' => 'question_id',
        		/*'required' => true,
        		'filters' => array(
        				array('name' => 'Int'),
        		),*/
        ));
        
        $this->add(array(
        		'name' => 'question_option',
        		/*'required' => false,
        		'filters' => array(
        				array('name' => 'Int'),
        		),*/
        ));
        
        $this->add(array(
        		'name' => 'correct',
        		/*'required' => false,
        		'filters' => array(
        				array('name' => 'Int'),
        		),*/
        )); 
	
    }
}