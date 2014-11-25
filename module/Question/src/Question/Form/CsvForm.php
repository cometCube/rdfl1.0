<?php
/**
 *@author : Ashwani Singh
 *@date : 01-10-2013
 *@desc : Album Form class
 */

namespace Question\Form;

use Zend\Form\Form;



class CsvForm extends Form 
{
	public function __construct($name = null)
	{
		parent::__construct('csvform');
		$this->url = $name;
		$this->setAttribute('method', 'post');
			$this->setAttribute('enctype','multipart/form-data');
		

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',	
		));
		
			$this->add(array(
				'name' => 'csv',
				'type'  => 'file',
									
				'options' => array(
									'label' => 'csv',
								),
				'attributes' => array(
						'id'    => 'csvuploadfile',
					'class' => 'form-control fileinpt',)
				));
	

		$this->add(array(
				'name' => 'submit',
				'type' => 'submit',
				'attributes' => array(
						 
						'id'    => 'csvsubmitbutton',
						'class'=>'btn',
				),
				'options' => array(
						'label' => 'Go',

				)
		));
		

	}



}