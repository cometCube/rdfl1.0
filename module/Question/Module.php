<?php
/*
 *@author : Ashwani Singh
 *@date : 30-09-2013  
 */

namespace Question;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Question\Model\Question;
use Question\Model\QuestionTable;

use Question\Model\Category;
use Question\Model\CategoryTable;

use Question\Model\Answer;
use Question\Model\AnswerTable;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class Module
{
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
    	return array(
    		'factories' => array(
    		    'Question\Model\QuestionTable' => function($sm) {
    		    	$tableGateway = $sm->get('QuestionTableGateway');
    		    	$table        = new QuestionTable($tableGateway);
    		    	return $table;
    		    },
    		    'QuestionTableGateway' => function($sm) {
    		    	$dbAdapter          = $sm->get('clientdb');
    		    	$resultSetPrototype = new ResultSet();
    		    	$resultSetPrototype->setArrayObjectPrototype(new Question());
    		    	return new TableGateway('questions', $dbAdapter, null, $resultSetPrototype);
    		    }, 
    		    
    		    'Question\Model\CategoryTable' => function($sm) {
    		    	$tableGateway = $sm->get('CategoryTableGateway');
    		    	$table        = new CategoryTable($tableGateway);
    		    	return $table;
    		    },
    		    
    		    'CategoryTableGateway' => function($sm) {
    		    	$dbAdapter          = $sm->get('clientdb');
    		    	$resultSetPrototype = new ResultSet();
    		    	$resultSetPrototype->setArrayObjectPrototype(new Category());
    		    	return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
    		    },
    		    
    		    'Question\Model\AnswerTable' => function($sm) {
    		    	$tableGateway = $sm->get('AnswerTableGateway');
    		    	$table        = new AnswerTable($tableGateway);
    		    	return $table;
    		    },
    		    'AnswerTableGateway' => function($sm) {
    		    	$dbAdapter          = $sm->get('clientdb');
    		    	$resultSetPrototype = new ResultSet();
    		    	$resultSetPrototype->setArrayObjectPrototype(new Answer());
    		    	return new TableGateway('question_options', $dbAdapter, null, $resultSetPrototype);
    		    },
    	    ),
    	);
    	
    }


}
