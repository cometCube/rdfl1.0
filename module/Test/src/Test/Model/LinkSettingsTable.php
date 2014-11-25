<?php
/*
* @author : Leena,Vijay
* @desc : Link join Test table Entity for Test module
* @created on : 01-07-2014
* ---------------------------------------------
* @modified on :
*
*
*
*/

namespace Test\Model;

use Zend\Cache\Storage\Adapter\ZendServerDisk;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;    

class LinkSettingsTable
{
    protected $tableGateway;
    protected $adapter;

    public function __construct(TableGateway $tableGateway) 
    {
        $this->tableGateway = $tableGateway;
        $this->adapter = $this->tableGateway->getAdapter();
    }

    public function fetchSettingsDetailsByLinkId($linkId)
    {
        $linkId = ( int ) $linkId;  

        $rowSet = $this->tableGateway->select ( array (
            'link_id' => $linkId
        ) );

        $row = $rowSet->current();
        return $row;
    }

    public function saveLinkSettings($linkSettings)
    {
        $userSession = new Container('users');

        $settingsData = array(
            'availability'                => $linkSettings['availability'],
            'attempts'                    => $linkSettings['attempts'],
            'password'                    => $linkSettings['password'],
            'language'                    => $linkSettings['language'],
            'firstname'                   => $linkSettings['firstName'],
            'lastname'                    => $linkSettings['lastName'],
            'time_limit'                  => $linkSettings['timeLimit'],
            'save_result'                 => $linkSettings['saveResult'],
            'resume_later'                => $linkSettings['resumeLater'],
            'total_question'              => $linkSettings['totalQuestion'],
            'display_questions'           => $linkSettings['displayQuestions'],
            'test_questions'              => $linkSettings['testQuestions'],
            'randomize'                   => $linkSettings['randomize'],
            'passing_marks'               => $linkSettings['passingMarks'],
            'message_test_competion_pass' => $linkSettings['endMessage'],
            'email_result'                => $linkSettings['emailResult'],
        );

        $customInstructionsData = array(
            'description'      => $linkSettings['customInstructionsId'],
        );

        $linkData = array(
            'test_id'    => $linkSettings['testId'],
            'name'       => $linkSettings['linkName'],
            'created_by' => $userSession-> id,
            'created_on' => date('Y-m-d h:i:s'),
        );



        $id = 0;

        if ($id == 0) {
            //insertion into customInstructions table
            if(strlen($linkSettings['customInstructionsId'])>20){
	            $sql = new Sql($this->adapter);
	            $insert = $sql->insert('custom_instructions');
	            $insert->values($customInstructionsData);
	            $selectString = $sql->getSqlStringForSqlObject($insert);
	            $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	            $lastCustomInstructionsId = $this->adapter->getDriver()->getLastGeneratedValue();
	        }
            
                //insertion into link table
                $sql = new Sql($this->adapter);
                $insert = $sql->insert('link');
                $insert->values($linkData);
                $selectString = $sql->getSqlStringForSqlObject($insert);
                $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
                $lastLinkId = $this->adapter->getDriver()->getLastGeneratedValue();


                if($results) {
                    //insertion into settings table(one setting for one link
                    
                    $settingsData['custom_instructions_id'] = ($lastCustomInstructionsId)?$lastCustomInstructionsId:1;
                    $settingsData['link_id'] = $lastLinkId;
                    $sql = new Sql($this->adapter);
                    $insert = $sql->insert('link_settings');
                    $insert->values($settingsData);
                    $selectString = $sql->getSqlStringForSqlObject($insert);
                    $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
                } else {
                    die('link creation failed due to some reason');
                }

        } else {
            if ($this->getTest($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('name id doesnt exists');
            }
        }
    }
}