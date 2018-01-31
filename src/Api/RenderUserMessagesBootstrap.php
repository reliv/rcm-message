<?php

namespace RcmMessage\Api;

use Psr\Http\Message\ServerRequestInterface;
use RcmMessage\Entity\UserMessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderUserMessagesBootstrap implements RenderUserMessages
{
    protected $prepareMessageForDisplay;
    protected $buildCssClassName;

    /**
     * @param PrepareMessageForDisplay $prepareMessageForDisplay
     * @param BuildCssClassName        $buildCssClassName
     */
    public function __construct(
        PrepareMessageForDisplay $prepareMessageForDisplay,
        BuildCssClassName $buildCssClassName
    ) {
        $this->prepareMessageForDisplay = $prepareMessageForDisplay;
        $this->buildCssClassName = $buildCssClassName;
    }

    /**
     * @param ServerRequestInterface $request
     * @param UserMessageInterface[] $userMessages
     * @param array                  $options
     *
     * @return string
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $userMessages,
        array $options = []
    ): string {
        $messageHtml = '';

        $userId = (array_key_exists(self::OPTION_USER_ID, $options) ? $options[self::OPTION_USER_ID] : '');

        $messageHtml .= '<div class="rcmMessage userMessageList" data-ng-controller="rcmMessageList">';

        foreach ($userMessages as $userMessage) {
            /** @var \RcmMessage\Entity\Message $message */
            $message = $this->prepareMessageForDisplay->__invoke(
                $userMessage->getMessage()
            );
            $cssName = $this->buildCssClassName->__invoke($message->getLevel());
            $messageSubject = $message->getSubject();
            $messageBody = $message->getMessage();

            $separator = ':';

            if (empty(trim($messageSubject)) || empty(trim($messageBody))) {
                $separator = '';
            }

            $messageHtml
                .= '
            <div class="' . $cssName . '" ng-hide="hiddenUserMessageIds[\''
                . $userId . ':' . $userMessage->getId() . '\']" role="alert">
              <button type="button" class="close" ng-click="dismissUserMessage('
                . $userId . ', ' . $userMessage->getId() . ')" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              <span class="subject">
              ' . $messageSubject . $separator . '
              </span>
              <span class="body">
              ' . $messageBody . '
              </span>
            </div>
            ';
        }
        $messageHtml .= '</div>';

        return $messageHtml;
    }
}
