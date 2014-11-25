
<?php 

return array(
		'controllers' => array(
				'invokables' => array(
						 'Certificate\Controller\Certificate' => 'Certificate\Controller\CertificateController',

				),
		),
		'router' => array(
	        'routes' => array(
	            'certificate' => array(
	                'type' => 'Segment',
		                'options' => array(
		                    'route'    => '/certificate[/:action][/:id]',
	                		'constraints' => array (
	                				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
	                				'id' => '[0-9]+'
	                		),
			                    'defaults' => array(
			                        'controller' => 'Certificate\Controller\Certificate',
			                        'action'     => 'index',
			                    ),
		                ),
	            ),
	           
	         ),
   		 ),
		
		'view_manager' => array(
				'template_path_stack' => array(
						__DIR__ . '/../view',
				),
				
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
		
		'asset_manager' => array(
				'resolver_configs' => array(
						'paths' => array(
								'Certificate' => __DIR__ . '/../public',
						),
				),
		),
		
);

