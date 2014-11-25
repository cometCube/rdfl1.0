<?php


namespace Test\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class LinkSettings implements InputFilterAwareInterface
{
    public $id ;
    public $link_id;
    public $availability;
    public $attempts;
    public $password;
    public $language;
    public $firstname;
    public $lastname;
    public $email;
    public $guidelines;
    public $custom_instructions_id;
    public $time_limit;
    public $save_result;
    public $resume_later;
    public $theme_id;
    public $display_questions;
    public $randomize;
    public $answer_mandatory;
    public $ques_feedback_duringtest;
    public $ques_feedback_aftertest;
    public $allow_answer_change;
    public $passing_marks;
    public $message_test_competion_pass;
    public $message_test_competion_fail;
    public $email_result;
    public $total_question;
    public $test_questions;

    protected $inputFilter;

    public function exchangeArray($data)
    {

        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->link_id = (!empty($data['link_id'])) ? $data['link_id'] : null;        
        $this->availability = (!empty($data['availability'])) ? $data['availability'] : null;
        $this->attempts = (!empty($data['attempts'])) ? $data['attempts'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->language = (!empty($data['language'])) ? $data['language'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname = (!empty($data['lastName'])) ? $data['lastName'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->guidelines = (!empty($data['guidelines'])) ? $data['guidelines'] : null;
        $this->custom_instructions_id = (!empty($data['custom_instructions_id'])) ? $data['custom_instructions_id'] : null;
        $this->time_limit = (!empty($data['time_limit'])) ? $data['time_limit'] : null;
        $this->save_result = (!empty($data['save_result'])) ? $data['save_result'] : null;
        $this->resume_later = (!empty($data['resume_later'])) ? $data['resume_later'] : null;
        $this->theme_id = (!empty($data['theme_id'])) ? $data['theme_id'] :null;
        $this->display_questions = (!empty($data['display_questions'])) ? $data['display_questions'] : null;
        $this->randomize = (!empty($data['randomize'])) ? $data['randomize'] : null;
        $this->answer_mandatory = (!empty($data['answer_mandatory'])) ? $data['answer_mandatory'] : null;
        $this->ques_feedback_duringtest = (!empty($data['ques_feedback_duringtest'])) ? $data['ques_feedback_duringtest'] : null;
        $this->ques_feedback_aftertest = (!empty($data['ques_feedback_aftertest'])) ? $data['ques_feedback_aftertest'] : null;
        $this->allow_answer_change = (!empty($data['allow_answer_change'])) ? $data['allow_answer_change'] : null;
        $this->passing_marks = (!empty($data['passing_marks'])) ? $data['passing_marks'] : null;
        $this->message_test_competion_pass = (!empty($data['message_test_competion_pass'])) ? $data['message_test_competion_pass'] : null;        
        $this->message_test_competion_fail = (!empty($data['message_test_competion_fail'])) ? $data['message_test_competion_fail'] : null;
        $this->email_result = (!empty($data['email_result'])) ? $data['email_result'] : null;
        $this->total_question = (!empty($data['total_question'])) ? $data['total_question'] : null;
        $this->test_questions = (!empty($data['testQuestions'])) ? $data['testQuestions'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            //link filter
            $inputFilter->add(array(
                'name'     => 'linkName',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            //test filter
            $inputFilter->add(array(
                'name'     => 'testId',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            //settings filter
            $inputFilter->add(array(
                'name'     => 'availability',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'attempts',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'password',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'language',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'firstName',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'lastName',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'email',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'guidelines',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'customInstructionsId',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'timeLimit',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'saveResult',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'resumeLater',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'totalQuestion',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'displayQuestions',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'testQuestions',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max'      => 400,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'randomize',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'displayResult',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'email_result',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'resultByCategory',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name'     => 'endMessage',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'passingMarks',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'emailResult',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}