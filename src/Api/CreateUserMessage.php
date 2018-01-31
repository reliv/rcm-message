<?php

namespace RcmMessage\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface CreateUserMessage
{
    /**
     * Creates a new user message and adds it to the message que db
     *
     * @param string $userId
     * @param string $subject
     * @param string $body
     * @param string $level
     * @param string $source
     * @param array $properties
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(
        $userId,
        $subject,
        $body,
        $level,
        $source,
        $properties = []
    );
}
