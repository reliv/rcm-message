<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserSitesAdmin implements IsAllowed
{
    protected $isAllowedRcmUser;

    /**
     * @param \RcmUser\Api\Acl\IsAllowed $isAllowedRcmUser
     */
    public function __construct(
        \RcmUser\Api\Acl\IsAllowed $isAllowedRcmUser
    ) {
        $this->isAllowedRcmUser = $isAllowedRcmUser;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool {
        return $this->isAllowedRcmUser->__invoke(
            $request,
            ResourceName::RESOURCE_SITES,
            'admin'
        );
    }
}
