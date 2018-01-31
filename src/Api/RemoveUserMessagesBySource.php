<?php

namespace RcmMessage\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RemoveUserMessagesBySource
{
    /**
     * @param string $userId
     * @param string $source
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(
        $userId,
        $source
    );
}
