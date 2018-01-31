<?php

namespace RcmMessage\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindUserMessages
{
    /**
     * @param string|int|null $userId
     * @param string|null     $source
     * @param int|null        $level
     * @param bool|null       $hasViewed
     *
     * @return array
     */
    public function __invoke(
        $userId,
        $source = null,
        $level = null,
        $hasViewed = null
    ): array;
}
