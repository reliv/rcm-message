<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayPurifyHtml implements PrepareMessageForDisplay
{
    protected $htmlPurifier;

    /**
     * @param \HTMLPurifier $htmlPurifier
     */
    public function __construct(
        \HTMLPurifier $htmlPurifier
    ) {
        $this->htmlPurifier = $htmlPurifier;
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
            $this->htmlPurifier->purify($message->getSubject())
        );

        $message->setMessage(
            $this->htmlPurifier->purify($message->getMessage())
        );

        return $message;
    }
}
