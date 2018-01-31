<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;
use Zend\I18n\Translator\TranslatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayTranslatorZf implements PrepareMessageForDisplay
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
        $message->setSubject(
            $this->translator->translate($message->getSubject())
        );

        $message->setMessage(
            $this->translator->translate($message->getMessage())
        );

        return $message;
    }
}
