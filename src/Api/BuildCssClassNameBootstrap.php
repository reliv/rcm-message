<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildCssClassNameBootstrap implements BuildCssClassName
{
    protected $cssMap
        = [
            MessageInterface::LEVEL_CRITICAL => 'alert alert-danger',
            MessageInterface::LEVEL_ERROR => 'alert alert-danger',
            MessageInterface::LEVEL_WARNING => 'alert alert-warning',
            MessageInterface::LEVEL_INFO => 'alert alert-info',
            MessageInterface::LEVEL_SUCCESS => 'alert alert-success',
        ];

    protected $defaultClass = 'alert alert-info';

    /**
     * @param int|null $level
     *
     * @return string
     */
    public function __invoke($level): string
    {
        if (empty($level)) {
            return $this->defaultClass;
        }

        if (isset($this->cssMap[$level])) {
            return $this->cssMap[$level];
        }

        return $this->defaultClass;
    }
}
