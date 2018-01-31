<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\GetPsrRequest;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetServerRequestRcmUser implements GetServerRequest
{
    /**
     * @return ServerRequestInterface
     */
    public function __invoke(): ServerRequestInterface
    {
        return GetPsrRequest::invoke();
    }
}
