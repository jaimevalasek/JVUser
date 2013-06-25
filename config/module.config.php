<?php

use Zend\Session\Container;
use Zend\Cache\StorageFactory;

return array(
    'useAcl' => true,
	'router' => array(
		'routes' => array(
			'user' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JVUser\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
			'cadastre-se' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/cadastre-se',
					'defaults' => array(
						'__NAMESPACE__' => 'JVUser\Controller',
						'controller'    => 'Index',
						'action'        => 'register',
					),
				),
			),
			'user-register' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/register',
					'defaults' => array(
						'__NAMESPACE__' => 'JVUser\Controller',
						'controller'    => 'Index',
						'action'        => 'register',
					),
				),
			),
			'user-activate' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/activate[/:token]',
					'defaults' => array(
						'__NAMESPACE__' => 'JVUser\Controller',
						'controller'    => 'Index',
						'action'        => 'activate',
					),
				),
			),
			'user-auth' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/auth[/:id][/:h1]',
					'defaults' => array(
						'__NAMESPACE__' => 'JVUser\Controller',
						'controller'    => 'Index',
						'action'        => 'auth',
					),
				),
			),
			'user-logout' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/logout',
					'defaults' => array(
						'__NAMESPACE__' => 'JVUser\Controller',
						'controller'    => 'Index',
						'action'        => 'logout',
					),
				),
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'JVUser\Controller\Index' => 'JVUser\Controller\IndexController',
		),
	),
	'service_manager' => array(
		'invokables' => array(
			'user_mapper_usuarios' => 'JVUser\Mapper\Usuario',
			'user_service_usuarios' => 'JVUser\Service\Usuario',
			'user_service_auth' => 'JVUser\Service\Auth',
			'user_service_acl' => 'JVUser\Service\Acl',
			'user_model_usuarios' => 'JVUser\Model\Usuario',
			'user_filter_usuarios' => 'JVUser\Filter\Usuario',
			'user_filter_auth' => 'JVUser\Filter\Auth',
			'user_form_usuarios' => 'JVUser\Form\Usuario',
			'user_form_auth' => 'JVUser\Form\Auth',
		),
		'factories' => array(
			'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
			'user_session_usuarios' => function ($sm) {
				return new Container('auth');
			},
		),
	),
	'view_helpers' => array(
	    'invokables' => array(
	        'permissoes' => 'JVUser\View\Helper\Permissoes',
	    )
	),
	'view_manager' => array(
		'display_not_found_reason' 	=> true,
		'display_exceptions'		=> true,
		'doctype'					=> 'HTML5',
		'not_found_template'       	=> 'error/404',
        'exception_template'       	=> 'error/index',
        'template_map' => array(
            'layout/layout'         => __DIR__ . '/../view/layout/user.phtml',
            'user/index/index' 		=> __DIR__ . '/../view/user/index/index.phtml',
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/index'           => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
	),
);