<?php
/**
 *@author : Divesh Pahuja
 *@date :   04-06-2014
 */

namespace Certificate\Form;

use Zend\Form\Form;



class CertificateForm extends Form 
{
	public function __construct($arrData)
	{
		parent::__construct('certificate');
		
		$testData = $arrData['tests'];
		foreach ($testData as $data) {
			$names[ $data[ 'id' ] ] = $data[ 'name' ];	
		}

		$assignedToData = $arrData['assignedTo'];
		$i = 0;
		foreach ($assignedToData as $data) {
			$assignedTo[$data['resultId']] = $data['name']." (".$data['startDate'].")";	
			$assigned[$i] = $data['name'];
			$i++;
		}
		
		//for selection of test 
		$this->add(array(
				'name' => 'testName',
				'type' => 'select',
				'options' => array(	
						'label' => 'First Select The Test',
						'value_options' => $names,
				),
				'attributes' => array(
						'id'    => 'testName',
						'class' => 'txtCertificate form-control'
				)
		));

		//for title input box		
        $this->add(array(
			'name' => 'title',
			'type' => 'Text',
			'options' => array(
			    'label' => 'Certificate title',
				
		    ),
		    'attributes' => array(
		    		'id'    => 'title',
		    		'class' => 'txtCertificate form-control',
		    		'required' => 'required',
		    		'value' => '',
            )		
		));
        
        //for assigned to selection
        $this->add(array(
        		'name' => 'assignedTo',
        		'type' => 'select',
        		'options' => array(
        				'label' => 'Assigned to',
        				'value_options' => $assignedTo,
        		),
        		'attributes' => array(
        				'id'    => 'assignedTo',
        				'class' => 'txtCertificate form-control',
        				'value' => '0', //set selected to '1'
        		)
        ));
        
        // hidden assigned to
        $this->add(array(
        		'name' => 'assigned_to',
        		'type' => 'Hidden',
        		'attributes' => array(
        				'id' => 'assigned_to',
        				'value' => $assigned[0],
        		),
        ));
        
        //for hidden email Id
        $this->add(array(
        		'name' => 'email',
        		'type' => 'Hidden',
        		'attributes' => array(
        				'id' => 'email',
        				'value' => "",
        		),
        ));
        
        //for certificate image
        $this->add(array(
        		'name' => 'certiImage',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'certiImage',
        
        		),
        		'attributes' => array(
        				'id'    => 'certiImage',
        				'value' => 'certificate1'
        		)
        ));
       
        //for email input box
        $this->add(array(
        		'name' => 'emailId',
        		'type' => 'Text',
        		'options' => array(
        				'label' => '',
        
        		),
        		'attributes' => array(
        				'id'    => 'certificate-email',
        				'class' => 'txtCertificate form-control',
        
        		)
        ));

        //for submission of certificate
		$this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Email Certificate',
                'id'    => 'submitCertificate',
            		'class'=>'btn'
            )
        ));
		
		//for preview
		$this->add(array(
				'name' => 'assigned_topre',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'assigned_topre',
				)
		));
		$this->add(array(
				'name' => 'titlepre',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'titlepre',
				)
		));
		$this->add(array(
				'name' => 'percenatge_pre',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'percenatge_pre',
				)
		));
		$this->add(array(
				'name' => 'date_pre',
				'type' => 'Hidden',
				'attributes' => array(
						'id'    => 'date_pre',
				)
		));
		
	}
}