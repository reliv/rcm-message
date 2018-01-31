<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetServerRequest
{
    /**
     * @return ServerRequestInterface
     */
    public function __invoke(): ServerRequestInterface;
}
