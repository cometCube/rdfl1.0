<?php
/*
 *@author : Ashwani Singh 
 *@date : 30-09-2013 
 *-----------------------------------------
 *        modified
 *-----------------------------------------
 *
 */

namespace Question\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Answer implements InputFilterAwareInterface
{
	public $id;
	public $question_id;
	public $question_option;
	public $correct;
    protected $inputFilter;
	
	public function exchangeArray($data)
	{
		
		
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->question_id = (!empty($data['question_id'])) ? $data['question_id'] : null;
		$this->question_option = (!empty($data['question_option'])) ? $data['question_option'] :null;
		$this->correct = (!empty($data['correct'])||$data['correct']==0) ? $data['correct'] : null;
	
		
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
            
            $inputFilter->add(array(
            		'name' => 'id',
            		/*'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),*/
            ));
            
            
            $inputFilter->add(array(
            		'name' => 'question_id',
            		/*'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),*/
            ));
            
            $inputFilter->add(array(
            		'name' => 'question_option',
            		/*'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),*/
            ));
            
            $inputFilter->add(array(
            		'name' => 'correct',
            		/*'required' => true,
            		'filters' => array(
            				array('name' => 'Int'),
            		),*/
            ));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}