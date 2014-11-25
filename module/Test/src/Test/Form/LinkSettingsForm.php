<?php


namespace Test\Form;

use Zend\Form\Form;

class LinkSettingsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('test');
        $this->url = $name;

        $this->add(array(
            'name' => 'resultByCategory',
            'type' => 'Radio',
            'options' => array(
                'value_options' => array(
                    '1' => 'Yes',
                    '0' => 'No',
                ),
            ),
            'attributes' => array(
                'value' => '0' //set checked to '2'
            )
        ));
        $this->add(array(
            'name' => 'passingMarks',
            'type' => 'Text',
            'options' => array(
                'label' => '<span>Passing Marks</span>',
            ),
            'attributes' => array(
                'value' => '50', //set checked to '2'
                'id' => 'topt-pam',
                'class' => 'textLarge',
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'id'    => 'btnGenerateLink',
            )
        ));
    }
}