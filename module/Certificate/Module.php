<?php

/*
 *@author : Divesh Pahuja
*@date : 30-06-2014
*$@modified : adding servie config for certificateTable date: 03-07-2014
*
*/

namespace Certificate;


use Certificate\Model\Certificate;
use Certificate\Model\CertificateTable;

use Student\Model\Student;
use Student\Model\StudentTable;

use Test\Model\Test;
use Test\Model\TestTable;

use Test\Model\Link;
use Test\Model\LinkTable;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

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
    
    public function getServiceConfig()
    {
        return array(
                'factories' => array(
                        'Certificate\Model\CertificateTable' => function($sm) {
                            
                            $tableGateway = $sm->get('CertificateTableGateway');
                            $table        = new CertificateTable($tableGateway);
                            return $table;
                        },
                        'CertificateTableGateway' => function($sm) {
                            $dbAdapter          = $sm->get('clientdb');
                            $resultSetPrototype = new ResultSet();
                            $resultSetPrototype->setArrayObjectPrototype(new Certificate());
                            return new TableGateway('certificate', $dbAdapter, null, $resultSetPrototype);
                        },
                        
//                         'Student\Model\StudentTable' => function($sm) {
                   
//                         	$tableGateway = $sm->get('StudentTableGateway');
//                         	$table        = new StudentTable($tableGateway);
//                         	return $table;
//                         },
//                         'StudentTableGateway' => function($sm) {
//                         	$dbAdapter          = $sm->get('clientdb');
//                         	$resultSetPrototype = new ResultSet();
//                         	$resultSetPrototype->setArrayObjectPrototype(new Student());
//                         	return new TableGateway('student', $dbAdapter, null, $resultSetPrototype);
//                         },
                        
                        'Test\Model\TestTable' => function($sm) {
                        	 
                        	$tableGateway = $sm->get('TestTableGateway');
                        	$table        = new TestTable($tableGateway);
                        	return $table;
                        },
                        'TestTableGateway' => function($sm) {
                        	$dbAdapter          = $sm->get('clientdb');
                        	$resultSetPrototype = new ResultSet();
                        	$resultSetPrototype->setArrayObjectPrototype(new Test());
                        	return new TableGateway('test', $dbAdapter, null, $resultSetPrototype);
                        },
                        
                        'Test\Model\LinkTable' => function($sm) {
                        
                        	$tableGateway = $sm->get('LinkTableGateway');
                        	$table        = new LinkTable($tableGateway);
                        	return $table;
                        },
                        'LinkTableGateway' => function($sm) {
                        	$dbAdapter          = $sm->get('clientdb');
                        	$resultSetPrototype = new ResultSet();
                        	$resultSetPrototype->setArrayObjectPrototype(new Test());
                        	return new TableGateway('link', $dbAdapter, null, $resultSetPrototype);
                        },
                ),
        );

    
    }
}
