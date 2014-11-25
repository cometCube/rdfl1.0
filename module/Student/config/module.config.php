<?php
return array (
		
		'controllers' => array (
				'invokables' => array (
						'Student\Controller\Student' => 'Student\Controller\StudentController' 
				)
				 
		),
		'router' => array (
				'routes' => array (
						'student' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/student[/:action][/:clientid][/:linkid][/:code]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'clientid' => '[a-zA-Z0-9_-]*', 
												'linkid' => '[0-9]+',
												'code' => '[a-zA-Z][a-zA-Z0-9_-]*',
												
										),
										'defaults' => array (
												'controller' => 'Student\Controller\Student',
												'action' => 'index' 
										) 
								) 
						) 
				) 
		),
		'asset_manager' => array(
				'resolver_configs' => array(
						'paths' => array(
								'Student' => __DIR__ . '/../public',
						),
				),
		),
		
		'view_manager' => array (
				'template_path_stack' => array (
						__DIR__ . '/../view' 
				) 
		) 
);
