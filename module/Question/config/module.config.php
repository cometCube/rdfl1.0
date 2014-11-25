<?php
/*
 *@author : Ashwani Singh
 *@date : 30-09-2013 
 *@desc : Album module config file 
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Question\Controller\Question' => 'Question\Controller\QuestionController',
        		

        ),
    ),
    'router' => array(
        'routes' => array(
        'question' => array(
        		'type' => 'Segment',
        		'options' => array(
        			'route' => '/question[/:action][/:id]',
        			'constraints' => array(
        				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        				'id' => '[0-9]+',
        			),
        			'defaults' => array(
        				'controller' => 'Question\Controller\Question',
        				'action' => 'index',
        			),
        		),
        	
           ),
        ),
    ),
   'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
   		'template_map' => array(
   				'download/download-csv' =>__DIR__ .'/../view/application/download/download-csv.phtml',
   		),
    ),
   'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                'Question' => __DIR__ . '/../public',
            ),
        ),
    ),
);

