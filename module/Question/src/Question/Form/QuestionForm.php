<?php

namespace Question\Form;

use Zend\Form\Form;

use Question\Model\CategoryTable;

use Question\Model\Question;


class QuestionForm extends Form 
{
	protected $category;
	public function __construct($category,$name = null)
	{
		
		$this->category = $category;
		parent::__construct('question');
		
		$this->url = $name;

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',	
		));
				
// 		$this->add(array(
// 				'name' => 'cat_id',
// 				'type' => 'Hidden',
// 		));
		
		$this->add(array(
				'name' => 'created_by',
				'type' => 'Hidden',
				'attributes' => array(
						'value'=>'1',
				
				)
		));

		$this->add(array(
				'name' => 'created_on',
				'type' => 'Hidden',
				'attributes' => array(
						'value'=>'0000',
				
				)
		));
		
		$this->add(array(
				'name' => 'updated_by',
				'type' => 'Hidden',
				'attributes' => array(
						'value'=>'1',
				
				)
		));
		
		$this->add(array(
				'name' => 'updated_on',
				'type' => 'Hidden',
				'attributes' => array(
						'value'=>'0000',
				
				)
		));
		
		$this->add(array(
				'type' => 'Select',
				'name' => 'cat_id',
				'options' => array(
						//'label' => 'Category',
						'label_attributes' => array(
								'class'  => 'categoryLabel'
						),
						'value_options' => $this->getOptionsForSelect(),
				),
				'attributes' => array(
						'id'=>'category',
						'class'=>'form-control width150',
				
				)
		));
		
		$this->add(array(
				'type' => 'Select',
				'name' => 'type',
				
				'options' => array(
						//'label' => 'Type',
						
						 'value_options' => array(
						 					//'2'=>'Select question type',
								            '1' => 'Multiple',
								            '0' => 'True False',
						 		 ),
						
				),
				'attributes' => array(
						'id'=>'type',
						'class'=>'form-control width150',
						
				)
				
		));
		
		
		
		$this->add(array(
					'name' => 'description',
					'type' => 'Textarea',
					
					'filters'    => array('StringTrim'),
					'options' => array(
							    'label' => 'Description',
								'label_attributes' => array(
									'class'  => 'classDescription'
						    ),
							),
				'attributes' => array(
						'class' => 'Description',
						'id'=>'description_id'
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
						'id'  => 'point'
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
				'name' => 'hdnCount',
				'type' => 'Hidden',
				'attributes' => array(
                'value' => '3',
						'id'=>'hdnCount',
               
            )
		));
		
 		$this->add(array(
             'name' => 'submit',
             'type' => 'submit',
            'attributes' => array(
               
                 'id'    => 'submitbuttonQuestion',
            		'class'=>'btn',
          ),
 				'options' => array(
 						'label' => 'Go'
 				)
         ));
	}
	
	public function getOptionsForSelect()
	{
		$selectData = array();
		$selectData[0]='Select Category';
		foreach ($this->category as $selectOption => $value) {
			
			$selectData[$value->id] = $value->name;
		}
		
		return $selectData;
	}
	
	
	



}