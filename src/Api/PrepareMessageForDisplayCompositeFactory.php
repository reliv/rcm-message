<?php

namespace RcmMessage\Api;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayCompositeFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return PrepareMessageForDisplayComposite
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        $appConfig = $serviceContainer->get('config');
        $config = $appConfig['rcm-message-prepare-message-services'];

        $service = new PrepareMessageForDisplayComposite();

        foreach ($config as $serviceName => $priority) {
            $service->add(
                $serviceContainer->get($serviceName),
                $priority
            );
        }

        return $service;
    }
}
