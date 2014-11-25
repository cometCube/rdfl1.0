<?php
/**
 *@author : Ashwani Singh
 *@date : 01-10-2013
 *@desc : Album Form class
 */

namespace Question\Form;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\InputFilter\InputFilter;


class CsvFormFilter extends InputFilter
{
	public function __construct()
	{
        $this->add(array(
			'name' => 'id',
			
		));
        
        
        $this->add(array(
        		'name' => 'csv',
        	
        ));
        
      
	
    }
}