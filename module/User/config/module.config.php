<?php
return array(

	 'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
        		'type' => 'Segment',
        		'options' => array(
        			'route' => '/user[/:action][/:id]',
        			'constraints' => array(
        				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        				'id' => '[0-9]*',
        			),
        			'defaults' => array(
        				'controller' => 'User\Controller\User',
        				'action' => 'login',
        			),
        		),
        	),
        ),  
        
    ),

   
   'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
   'module_config' => array(
'upload_location' => $_SERVER['DOCUMENT_ROOT'] . '/profilePics',
),

);