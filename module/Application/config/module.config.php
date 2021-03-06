<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Method;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySQLDriver;

return [
    'router' => [
        'routes' => [
            'upload' => [
                'type'=>Literal::class,
                'options' => [
                    'route'=> '/upload',
                    'defaults'=> [
                        'controller'=> Controller\IndexController::class,
                        'action'=> 'upload',
                    ],
                ],
            ],
            'login' => [
                'type'=>Literal::class,
                'options' => [
                    'route'=> '/login',
                    'defaults'=> [
                        'controller'=> Controller\IndexController::class,
                        'action'=> 'login',
                    ],
                ],
            ],
            'level' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/level[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\LevelController::class,
                    ],
                ],
            ],
	    'transactions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/transactions[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\TransactionController::class,
                    ],
                ],
            ],
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/user[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                    ],
                ],
            ],
            'privileges' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/privileges[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\PrivilegesController::class,
                    ],
                ],
            ],
            'dumps' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/api/dump[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\DumpController::class,
                    ],
                ],
            ],
            'map' => [
		'type' => Segment::class,
		'options' => [
		    'route' => '/map',
		    'defaults' => [
			'controller' => Controller\IndexController::class,
			'action' => 'map',
		    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'myroute2get' => [                  // This child route will match GET request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action'     => 'map'
                            ],
                        ],
                    ],
                    'myroute2post' => [                 // This child route will match POST request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action'     => 'savepoint'
                            ],
                        ],
                    ],
                ],
	    ],
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
	    Controller\UserController::class => Controller\Factories\IndexControllerFactory::class,
            Controller\IndexController::class => Controller\Factories\IndexControllerFactory::class,
            Controller\DumpController::class =>  Controller\Factories\IndexControllerFactory::class,
	    Controller\PrivilegesController::class => Controller\Factories\IndexControllerFactory::class,
            Controller\LevelController::class => Controller\Factories\IndexControllerFactory::class,
	    Controller\TransactionController::class => Controller\Factories\IndexControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'map/map'		      => __DIR__ . '/../view/application/map.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
	'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'localhost',
                    'user'     => 'root',
                    'password' => 'biberon',
                    'dbname'   => 'zend',
                    'charset' => 'utf8',
		    'unix_socket'=> '/var/run/mysqld/mysqld.sock',
                ]
            ]
	],
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
