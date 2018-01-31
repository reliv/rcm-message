<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetCurrentUserId
{
    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return mixed|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    );
}
