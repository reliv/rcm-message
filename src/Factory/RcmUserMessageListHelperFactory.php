<?php

namespace RcmMessage\Factory;

use Interop\Container\ContainerInterface;
use RcmMessage\View\Helper\RcmUserMessageListHelper;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

/**
 * Class RcmUserMessageListHelperFactory
 *
 * PHP version 5
 *
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
     * @param ContainerInterface|ServiceLocatorInterface|HelperPluginManager $container
     *
     * @return RcmUserMessageListHelper
     */
    public function __invoke($container)
    {
        if ($container instanceof HelperPluginManager) {
            $container = $container->getServiceLocator();
        }

        $userMessageRepo = $container->get('Doctrine\ORM\EntityManager')->getRepository(
            '\RcmMessage\Entity\UserMessage'
        );
        $rcmUserService = $container->get('RcmUser\Service\RcmUserService');
        $translator = $container->get('MvcTranslator');

        return new RcmUserMessageListHelper(
            $userMessageRepo,
            $rcmUserService,
            $translator,
            $container->get('RcmHtmlPurifier')
        );
    }
}
