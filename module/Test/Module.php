<?php
/*
* @author : Leena,Vijay
* @desc :  Test module Config
* @created on : 30-06-2014
* ---------------------------------------------
* @modified on :
*
*
*
*/
namespace Test;

use Test\Model\Test;
use Test\Model\TestTable;

use Test\Model\LinkSettings;
use Test\Model\LinkSettingsTable;

use Test\Model\TestQuestionsTable;

use Test\Model\Link;
use Test\Model\LinkTable;

use Test\Model\LinkAssignDates;
use Test\Model\LinkAssignDatesTable;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class Module {
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getServiceConfig() {
		return array (
				'factories' => array (
                        //for test table
						'Test\Model\TestTable' => function ($sm) {
							$tableGateway = $sm->get ( 'TestTableGateway' );
							$table = new TestTable ( $tableGateway );
							return $table;
						},
						'TestTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'clientdb' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Test () );
							return new TableGateway ( 'test', $dbAdapter, null, $resultSetPrototype );
						},

                        //for test join question table
						'Test\Model\TestQuestionsTable' => function ($sm) {
							$dbAdapter = $sm->get ( 'clientdb' );
							$table = new TestQuestionsTable ( $dbAdapter );
							return $table;
						},

                        // for link table
                        'Test\Model\LinkTable' => function($sm) {
                                $tableGateway = $sm->get('LinkTableGateway');
                                $table        = new LinkTable($tableGateway);
                                return $table;
                        },
                        'LinkTableGateway' => function ($sm) {
                                $dbAdapter = $sm->get ( 'clientdb' );
                                $resultSetPrototype = new ResultSet ();
                                $resultSetPrototype->setArrayObjectPrototype ( new Link () );
                                return new TableGateway ( 'link', $dbAdapter, null, $resultSetPrototype );
                        },

                        //for link_settings table
                        'Test\Model\LinkSettingsTable' => function ($sm) {
                            $tableGateway = $sm->get ( 'LinkSettingsTableGateway' );
                            $table = new LinkSettingsTable ( $tableGateway );
                            return $table;
                        },
                        'LinkSettingsTableGateway' => function ($sm) {
                            $dbAdapter = $sm->get ( 'clientdb' );
                            $resultSetPrototype = new ResultSet ();
                            $resultSetPrototype->setArrayObjectPrototype ( new LinkSettings());
                            return new TableGateway ( 'link_settings', $dbAdapter, null, $resultSetPrototype );
                        },

                        //for LinkAssignDates table
                        'Test\Model\LinkAssignDatesTable' => function ($sm) {
                            $tableGateway = $sm->get ( 'LinkAssignDatesTableGateway' );
                            $table = new LinkAssignDatesTable ( $tableGateway );
                            return $table;
                        },
                        'LinkAssignDatesTableGateway' => function ($sm) {
                            $dbAdapter = $sm->get ( 'clientdb' );
                            $resultSetPrototype = new ResultSet ();
                            $resultSetPrototype->setArrayObjectPrototype ( new LinkAssignDates());
                            return new TableGateway ( 'link_assign_dates', $dbAdapter, null, $resultSetPrototype );
                        },
				) 
		);
	}
}