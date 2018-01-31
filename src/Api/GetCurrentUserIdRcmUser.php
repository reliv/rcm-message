<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetCurrentUser;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetCurrentUserIdRcmUser implements GetCurrentUserId
{
    protected $getCurrentUser;

    /**
     * @param GetCurrentUser $getCurrentUser
     */
    public function __construct(
        GetCurrentUser $getCurrentUser
    ) {
        $this->getCurrentUser = $getCurrentUser;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return mixed|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $user = $this->getCurrentUser->__invoke(
            $request
        );

        if (empty($user)) {
            return null;
        }

        return $user->getId();
    }
}
