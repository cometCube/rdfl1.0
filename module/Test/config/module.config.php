<?php

/*
* @author : Leena,Vijay
* @desc : Main controller for Test module
* @created on : 30-06-2014
* ---------------------------------------------
* @modified on : 
* 
*
*
*/

return array (
		'controllers' => array (
				'invokables' => array (
						
						'Test\Controller\Test' => 'Test\Controller\TestController' 
				) 
		),
		'router' => array (
				'routes' => array (
						'test' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/test[/:action][/:id][/:param]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+',
												'param' => '[0-9]+',
										),
										'defaults' => array (
												'controller' => 'Test\Controller\Test',
												'action' => 'index' 
										) 
								) 
						) 
				) 
		),
		'view_manager' => array (
				'template_path_stack' => array (
						__DIR__ . '/../view' 
				) 
		) 
);
        		
        		