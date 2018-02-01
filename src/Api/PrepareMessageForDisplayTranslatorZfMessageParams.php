<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;
use Zend\I18n\Translator\TranslatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayTranslatorZfMessageParams implements PrepareMessageForDisplay
{
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

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

        if (!array_key_exists(PrepareMessageForDisplayMessageParams::PROPERTY_MESSAGE_PARAMS, $properties)) {
            return $message;
        }

        $messageParams = (array)$properties[PrepareMessageForDisplayMessageParams::PROPERTY_MESSAGE_PARAMS];

        foreach ($messageParams as $param => $messageParam) {
            $messageParams[$param] = $this->translator->translate($messageParam);
        }

        $properties[PrepareMessageForDisplayMessageParams::PROPERTY_MESSAGE_PARAMS] = $messageParams;

        $message->setProperties(
            $properties
        );

        return $message;
    }
}
