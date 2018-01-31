<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface PrepareMessageForDisplay
{
    /**
     * @param MessageInterface $message
     * @param array            $options
     *
     * @return MessageInterface
     */
    public function __invoke(
        MessageInterface $message,
        array $options = []
    ): MessageInterface;
}
