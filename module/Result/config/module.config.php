<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Result\Controller\Result' => 'Result\Controller\ResultController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'result' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/result[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Result\Controller\Result',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
    ),

);