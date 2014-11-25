<?php
namespace Student;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Session\Container;

use Student\Model\StudentTable;
use Zend\Db\Adapter\Adapter;

class Module
{
	protected $dbAdapter;
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
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $this->config  = $sm->get('config');
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
       // $eventManager->attach('dispatch', array($this, 'loadConfiguration' ),1);
      
    }
	
	public function loadConfiguration(MvcEvent $e) {
		
		 
        $routeMatch = $e->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        
		//$controller->layout()->modulenamespace = $moduleNamespace;
		
		
		/* $dbAdapterConfig = array(
		 'driver'         =>  'Pdo',
				'dsn'            =>  'mysql:dbname=clientdb0'.$clientId.';host=localhost',
				'username' => 'client0'.$clientId,
				'password' => 'client0'.$clientId,
		); */
		 
		//$this->dbAdapter = new Adapter($dbAdapterConfig);
	}
    
    public function getServiceConfig()
    {
    	
    	return array(
    			'factories' => array(
    					'Student\Model\StudentTable' => function($sm) {
   
    						//$tableGateway = $sm->get('StudentTableGateway');
    						$table        = new StudentTable();
    						return $table;
    					},
    					 /* 'StudentTableGateway' => function($sm) {
    						$dbAdapter          = $this->dbAdapter;
    						//$resultSetPrototype = new ResultSet();
    						//$resultSetPrototype->setArrayObjectPrototype(new Certificate());
    						return new TableGateway('student', $dbAdapter, null);
    					},  */
    			),
    	);
    
    }
    
    
}
