<?php
namespace Result;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

//use Result\Model\Result;
use Result\Model\ResultTable;
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
                'Result\Model\ResultTable' => function($sm) {
                    $tableGateway = $sm->get('ResultTableGateway');
                    $table        = new ResultTable($tableGateway);
                    return $table;
                },
                'ResultTableGateway' => function($sm) {
                    $dbAdapter          = $sm->get('clientdb');                    
                    $resultSetPrototype = new ResultSet();                    
                    //$resultSetPrototype->setArrayObjectPrototype(new Result());
                    return new TableGateway('result', $dbAdapter, null, $resultSetPrototype);
                }, 
            ),
        );
        
    }
}
