<?php
namespace Result\Form;
use Zend\Form\Form;
class ResultForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Result');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'Email',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password',
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'id' => 'btnSubmit',
            ),
        ));

    }

}
