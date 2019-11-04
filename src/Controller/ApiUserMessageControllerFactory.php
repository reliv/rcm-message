<?php

namespace RcmMessage\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use RcmMessage\Api\GetCurrentUserId;
use RcmMessage\Api\GetServerRequest;
//use RcmMessage\Api\IsAllowedRcmUserSitesAdmin;
use RcmMessage\Api\PrepareMessageForDisplay;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiUserMessageControllerFactory
{
    /**
     * @param $serviceContainer ContainerInterface|ServiceLocatorInterface|ControllerManager
     *
     * @return ApiUserMessageController
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        // @BC for ZendFramework
        if ($serviceContainer instanceof ControllerManager) {
            $serviceContainer = $serviceContainer->getServiceLocator();
        }

        $controller = new ApiUserMessageController(
            $serviceContainer->get(EntityManager::class),
//            $serviceContainer->get(IsAllowedRcmUserSitesAdmin::class),
            $serviceContainer->get(GetServerRequest::class),
            $serviceContainer->get(GetCurrentUserId::class),
            $serviceContainer->get(PrepareMessageForDisplay::class),
            []
        );

        if (method_exists($controller, 'setServiceLocator')) {
            $controller->setServiceLocator($serviceContainer);
        }

        return $controller;
    }
}
