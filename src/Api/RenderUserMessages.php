<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;
use RcmMessage\Entity\UserMessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RenderUserMessages
{
    const OPTION_USER_ID = 'user-id';

    /**
     * @param ServerRequestInterface $request
     * @param UserMessageInterface[] $userMessages
     * @param array                  $options
     *
     * @return string
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $userMessages,
        array $options = []
    ): string;
}
