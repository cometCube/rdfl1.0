<?php
/**
 *@author : Ashwani Singh
 *@date : 01-10-2013
 *@desc : Album Form class
 */

namespace Question\Form;

use Zend\Form\Form;



class EditquestionForm extends Form 
{
	public function __construct($i,$name = null)
	{
		parent::__construct('editquestion');
		$this->url = $name;

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',	
		));
				
		$this->add(array(
				'name' => 'cat_id',
				'type' => 'Hidden',
		));
		
		$this->add(array(
				'name' => 'created_by',
				'type' => 'Hidden',
		));

		$this->add(array(
				'name' => 'created_on',
				'type' => 'Hidden',
		));
		
		$this->add(array(
				'name' => 'updated_by',
				'type' => 'Hidden',
		));
		
		$this->add(array(
				'name' => 'updated_on',
				'type' => 'Hidden',
		));
		
		$this->add(array(
					'name' => 'description',
					'type' => 'Textarea',
					'options' => array(
							   
							'label_attributes' => array(
									'class'  => 'classDescription'
						    ),
							),
				'attributes' => array(
						'class' => 'Description',
						'id'  =>'description_id'
				)
				));
		
		$this->add(array(
				'name' => 'points',
				'type' => 'Text',
				'options' => array(
						'label' => 'Points',
						'label_attributes' => array(
								'class'  => 'classDescription'
						),
				),
				'attributes' => array(
						'class' => 'points',
						'id'  =>'point'
				)
		));
		
		$this->add(array(
				'name' => 'status',
				'type' => 'Hidden',
				'attributes' => array(
						'value'=>'0',
				
				)
		));
		
		$this->add(array(
				'type' => 'Zend\Form\Element\Select',
				'name' => 'type',

				'attributes' =>  array(
						'id' => 'type',
						'class' => 'form-control width150',
						
						
						'options' => array(
											
								  			'1' => 'Multiple',
								            '0' => 'True False',
								
						),

				),
		
		));
		

		$this->add(array(
				'name' => 'hdnCount',
				'type' => 'Hidden',
				'attributes' => array(
						'value' => $i-1,
						'id'=>'hdnCount',
						 
				)
		));
		
		
 		$this->add(array(
             'name' => 'submit',
             'type' => 'submit',
            'attributes' => array(
               
                 'id'    => 'editsubmitbutton',
            	'class' =>'btn btn-primary',
            		//'data-dismiss'=> 'modal',
          ),
 				'options' => array(
 						'label' => 'save Question'
 				)
         ));
 		
 		$this->add(array(
 				'name' => 'cancel',
 				'type' => 'button',
 				'attributes' => array(
 						 
 						'id'    => 'cancelbutton',
 						'class' =>'btn btn-primary',
 				),
 				'options' => array(
 						'label' => 'cancel'
 				)
 		));
	}



}