<?php

namespace RcmMessage\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface BuildCssClassName
{
    /**
     * @param int|null $level
     *
     * @return string
     */
    public function __invoke($level): string;
}
