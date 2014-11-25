<?php


namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Form\Element\Input;



class AddUserForm extends Form 
{
	public function __construct($name = null)
	{
		parent::__construct('addUser');
		$this->url = $name;

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',	
		));

		 $this->add(array(
			'name' => 'email',
			'type' => 'Text',
			'options' => array(
			    'label' => 'Email',
		    ),

		     'attributes' => array(
		    		'class' => 'light-box-content-textbox',
		    		'id' => 'txtEmail', 
		    		'placeholder' => 'Enter Email'
		    		
		    	),		
		));

		 $this->add(array(
			'name' => 'username',
			'type' => 'Text',
			'options' => array(
			    'label' => 'User Name',
		    ),

		     'attributes' => array(
		    		'class' => 'light-box-content-textbox',
		    		'id' => 'txtRegUsername',
		    		'placeholder' => 'Enter Username' 
		    		
		    	),		
		)); 


$this->add(array(
			'name' => 'firstname',
			'type' => 'Text',
			'options' => array(
			    'label' => 'First Name',
		    ),

		     'attributes' => array(
		    		'class' => 'light-box-content-textbox',
		    		'id' => 'txtRegFirstname',
		    		'placeholder' => 'Enter Firstname' 
		    		
		    	),		
		)); 



$this->add(array(
			'name' => 'lastname',
			'type' => 'Text',
			'options' => array(
			    'label' => 'Last Name',
		    ),

		     'attributes' => array(
		    		'class' => 'light-box-content-textbox',
		    		'id' => 'txtRegLastname',
		    		'placeholder' => 'Enter Lastname' 
		    		
		    	),		
		)); 



		 $this->add(array(
			'name' => 'password',
			'type' => 'password',
			'options' => array(
			    'label' => 'Create Password',
		    ),

		     'attributes' => array(
		    		'class' => 'light-box-content-textbox',
		    		'id' => 'txtPass',
		    		'placeholder' => 'Enter Password' 
		    		
		    	),		
		)); 

		  $this->add(array(
			'name' => 'confirmpassword',
			'type' => 'password',
			'options' => array(
			    'label' => 'Confirm Password',
		    ),

		     'attributes' => array(
		    		'class' => 'light-box-content-textbox',
		    		'id' => 'txtconfirmPass',
		    		'placeholder' => 'Confirm Password' 
		    		
		    	),		
		)); 

		$this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Register',
                
            ),

            'attributes' => array(
            	'id'    => 'btnRegister',
		    		'class' => 'btn float-left'
		    	),
        ));

         
 
	}
}