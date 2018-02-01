<?php

namespace RcmMessage;

use Doctrine\ORM\EntityManager;
use RcmMessage\Api\BuildCssClassName;
use RcmMessage\Api\BuildCssClassNameBootstrap;
use RcmMessage\Api\CreateUserMessage;
use RcmMessage\Api\CreateUserMessageDoctrine;
use RcmMessage\Api\FindUserMessages;
use RcmMessage\Api\FindUserMessagesDoctrine;
use RcmMessage\Api\GetCurrentUserId;
use RcmMessage\Api\GetCurrentUserIdRcmUser;
use RcmMessage\Api\GetServerRequest;
use RcmMessage\Api\GetServerRequestRcmUser;
use RcmMessage\Api\IsAllowedRcmUserSitesAdmin;
use RcmMessage\Api\PrepareMessageForDisplay;
use RcmMessage\Api\PrepareMessageForDisplayCompositeFactory;
use RcmMessage\Api\PrepareMessageForDisplayMessageParams;
use RcmMessage\Api\PrepareMessageForDisplayPurifyHtml;
use RcmMessage\Api\PrepareMessageForDisplayTranslatorZf;
use RcmMessage\Api\PrepareMessageForDisplayTranslatorZfMessageParams;
use RcmMessage\Api\RemoveUserMessagesBySource;
use RcmMessage\Api\RemoveUserMessagesBySourceDoctrine;
use RcmMessage\Api\RenderUserMessages;
use RcmMessage\Api\RenderUserMessagesBootstrap;
use RcmMessage\Model\MessageManager;
use RcmUser\Api\Authentication\GetCurrentUser;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   RcmMessage
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Module
{
    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return [
            /**
             * asset_manager
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
             * controllers
             */
            'controllers' => [
                'factories' => [
                    \RcmMessage\Controller\ApiUserMessageController::class
                    => \RcmMessage\Controller\ApiUserMessageControllerFactory::class,
                ],
                'invokables' => [
                    \RcmMessage\Controller\MessageListController::class
                    => \RcmMessage\Controller\MessageListController::class,
                ],
            ],
            /**
             * doctrine
             */
            'doctrine' => array(
                'driver' => array(
                    'RcmMessage' => array(
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => array(
                            __DIR__ . '/Entity',
                        )
                    ),
                    'orm_default' => array(
                        'drivers' => array(
                            'RcmMessage' => 'RcmMessage'
                        )
                    )
                )
            ),
            'rcm-message-prepare-message-services' => [
                PrepareMessageForDisplayPurifyHtml::class => -10, // should always be last
                PrepareMessageForDisplayMessageParams::class => 5, // should always be after translate
                PrepareMessageForDisplayTranslatorZf::class => 10, // should always be near last
                PrepareMessageForDisplayTranslatorZfMessageParams::class => 15 // should always be near last
            ],
            /**
             * router
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
             * service_manager
             */
            'service_manager' => [
                'config_factories' => [
                    BuildCssClassName::class => [
                        'class' => BuildCssClassNameBootstrap::class,
                    ],
                    CreateUserMessage::class => [
                        'class' => CreateUserMessageDoctrine::class,
                        'arguments' => [
                            EntityManager::class
                        ]
                    ],
                    FindUserMessages::class => [
                        'class' => FindUserMessagesDoctrine::class,
                        'arguments' => [
                            EntityManager::class
                        ]
                    ],
                    GetCurrentUserId::class => [
                        'class' => GetCurrentUserIdRcmUser::class,
                        'arguments' => [
                            GetCurrentUser::class
                        ]
                    ],

                    GetServerRequest::class => [
                        'class' => GetServerRequestRcmUser::class,
                    ],

                    IsAllowedRcmUserSitesAdmin::class => [
                        'arguments' => [
                            \RcmUser\Api\Acl\IsAllowed::class
                        ]
                    ],

                    PrepareMessageForDisplay::class => [
                        'factory' => PrepareMessageForDisplayCompositeFactory::class,
                    ],

                    PrepareMessageForDisplayMessageParams::class => [],

                    PrepareMessageForDisplayPurifyHtml::class => [
                        'arguments' => [
                            'RcmHtmlPurifier'
                        ]
                    ],

                    PrepareMessageForDisplayTranslatorZf::class => [
                        'arguments' => [
                            'MvcTranslator'
                        ]
                    ],

                    PrepareMessageForDisplayTranslatorZfMessageParams::class => [
                        'arguments' => [
                            'MvcTranslator'
                        ]
                    ],

                    RemoveUserMessagesBySource::class => [
                        'class' => RemoveUserMessagesBySourceDoctrine::class,
                        'arguments' => [
                            EntityManager::class
                        ]
                    ],

                    RenderUserMessages::class => [
                        'class' => RenderUserMessagesBootstrap::class,
                        'arguments' => [
                            PrepareMessageForDisplay::class,
                            BuildCssClassName::class
                        ]
                    ],

                    MessageManager::class => [
                        'arguments' => [
                            EntityManager::class
                        ]
                    ]
                ]
            ],
            /**
             * view_helpers
             */
            'view_helpers' => [
                'factories' => [
                    'rcmMessageUserMessageList'
                    => \RcmMessage\View\Helper\RcmUserMessageListHelperFactory::class,
                ],
                'invokables' => [
                    'rcmMessageFlashMessageList'
                    => \RcmMessage\View\Helper\RcmFlashMessageListHelper::class,
                ],
            ],
            /**
             * view_helper_config
             */
            'view_helper_config' => [
                'flashmessenger' => [
                    'message_open_format' => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><div><ul><li><i></i>',
                    'message_close_string' => '</li></ul></div></div>',
                    'message_separator_string' => '</li><li>'
                ],
            ],
            /**
             * view_manager
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
    }
}
