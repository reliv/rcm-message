<?php
/**
 * Config
 */
return [
    /**
     *
     */
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-message/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm/modules.js' => [
                    'modules/rcm-message/js/rcm-message.js'
                ],
                'modules/rcm/modules.css' => [
                    'modules/rcm-message/css/styles.css'
                ],
            ],
        ],
    ],
    /**
     *
     */
    'controllers' => [
        'invokables' => [
            \RcmMessage\Controller\MessageListController::class => \RcmMessage\Controller\MessageListController::class,
            \RcmMessage\Controller\ApiUserMessageController::class => \RcmMessage\Controller\ApiUserMessageController::class,
        ],
    ],
    /**
     *
     */
    'doctrine' => array(
        'driver' => array(
            'RcmMessage' => array(
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Entity',
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'RcmMessage' => 'RcmMessage'
                )
            )
        )
    ),
    /**
     *
     */
    'router' => [
        'routes' => [
            'RcmMessageList' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/my-system-messages',
                    'defaults' => [
                        'controller' => \RcmMessage\Controller\MessageListController::class,
                        'action' => 'index',
                        'messageFilters' => [
                            'source' => null,
                            'level' => null,
                            'showHasViewed' => false
                        ],
                    ],

                ],
            ],
            'RcmMessage\ApiUserMessage' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/message/user/:userId/message[/:id]',
                    'defaults' => [
                        'controller' => \RcmMessage\Controller\ApiUserMessageController::class,
                    ]
                ],
            ],
        ],
    ],
    /**
     *
     */
    'service_manager' => [
        'config_factories' => [
            \RcmMessage\Model\MessageManager::class => [
                'arguments' => [
                    'Doctrine\Orm\EntityManager'
                ]
            ]
        ]
    ],
    /**
     *
     */
    'view_helpers' => [
        'factories' => [
            'rcmMessageUserMessageList' => \RcmMessage\Factory\RcmUserMessageListHelperFactory::class,
        ],
        'invokables' => [
            'rcmMessageFlashMessageList' => \RcmMessage\View\Helper\RcmFlashMessageListHelper::class,
        ],
    ],
    /**
     *
     */
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format' => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><div><ul><li><i></i>',
            'message_close_string' => '</li></ul></div></div>',
            'message_separator_string' => '</li><li>'
        ],
    ],
    /**
     *
     */
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
