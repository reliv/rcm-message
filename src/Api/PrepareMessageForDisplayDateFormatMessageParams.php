<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayDateFormatMessageParams implements PrepareMessageForDisplay
{
    const PROPERTY_DATE_MESSAGE_PARAMS = 'date-message-params';

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
        if (!class_exists('\IntlDateFormatter')) {
            // INTL extension required
            return $message;
        }

        $properties = $message->getProperties();

        if (!array_key_exists(self::PROPERTY_DATE_MESSAGE_PARAMS, $properties)) {
            return $message;
        }

        $messageParams = (array)$properties[PrepareMessageForDisplayMessageParams::PROPERTY_MESSAGE_PARAMS];
        $dateMessageParams = (array)$properties[self::PROPERTY_DATE_MESSAGE_PARAMS];

        $dateFormatter = new \IntlDateFormatter(
            setlocale(LC_TIME, 0),
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE
        );

        foreach ($dateMessageParams as $param => $dateMessageParam) {
            if (!array_key_exists($dateMessageParam, $messageParams)) {
                continue;
            }

            $messageParams[$dateMessageParam] = $dateFormatter->format($messageParams[$dateMessageParam]);
        }

        $properties[PrepareMessageForDisplayMessageParams::PROPERTY_MESSAGE_PARAMS] = $messageParams;

        $message->setProperties(
            $properties
        );

        return $message;
    }
}
