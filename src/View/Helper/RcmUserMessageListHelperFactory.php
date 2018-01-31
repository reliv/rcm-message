<?php

namespace RcmMessage\View\Helper;

use Interop\Container\ContainerInterface;
use RcmMessage\Api\FindUserMessages;
use RcmMessage\Api\GetCurrentUserId;
use RcmMessage\Api\GetServerRequest;
use RcmMessage\Api\RenderUserMessages;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

/**
 * @category  Reliv
 * @package   RcmMessage\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserMessageListHelperFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface|HelperPluginManager $serviceContainer
     *
     * @return RcmUserMessageListHelper
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        if ($serviceContainer instanceof HelperPluginManager) {
            $serviceContainer = $serviceContainer->getServiceLocator();
        }

        return new RcmUserMessageListHelper(
            $serviceContainer->get(FindUserMessages::class),
            $serviceContainer->get(GetServerRequest::class),
            $serviceContainer->get(GetCurrentUserId::class),
            $serviceContainer->get(RenderUserMessages::class)
        );
    }
}
