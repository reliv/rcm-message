<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayMessageParams implements PrepareMessageForDisplay
{
    const PROPERTY_MESSAGE_PARAMS = 'message-params';

    /**
     * @param MessageInterface $message
     * @param array            $options
     *
     * @return MessageInterface
     */
    public function __invoke(
        MessageInterface $message,
        array $options = []
    ): MessageInterface {
        $properties = $message->getProperties();

        $messageParams = [];

        if (array_key_exists(self::PROPERTY_MESSAGE_PARAMS, $properties)) {
            $messageParams = (array)$properties[self::PROPERTY_MESSAGE_PARAMS];
        }

        $subject = $message->getSubject();
        $messageStr = $message->getMessage();

        foreach ($messageParams as $param => $messageParam) {
            $subject = str_replace(
                '{' . $param . '}',
                $messageParam,
                $subject
            );

            $messageStr = str_replace(
                '{' . $param . '}',
                $messageParam,
                $messageStr
            );
        }

        $message->setSubject(
            $subject
        );

        $message->setMessage(
            $messageStr
        );

        return $message;
    }
}
