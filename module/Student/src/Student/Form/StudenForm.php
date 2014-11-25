 <?php
/**
 *@author : Dhirendra Pandey
 *@date    : 30-06-2014
 *@desc   : Student Login Form
 */
namespace Student\Form;
use Zend\Form\Form;
class StudentForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('student');
        $this->url = $name;
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'firstname',
            'type' => 'Text',
            'options' => array(
                'label' => 'First Name'
            )
        ));
        $this->add(array(
            'name' => 'lastname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Last Name'
            )
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email'
            )
        ));
        $this->add(array(
            'name' => 'login',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Login',
                'id' => 'loginbtn'
            )
        ));
    }
} 