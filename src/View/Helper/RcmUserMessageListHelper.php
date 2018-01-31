<?php

namespace RcmMessage\View\Helper;

use RcmMessage\Api\FindUserMessages;
use RcmMessage\Api\GetCurrentUserId;
use RcmMessage\Api\GetServerRequest;
use RcmMessage\Api\RenderUserMessages;
use RcmMessage\Entity\Message as MessageEntity;
use Zend\View\Helper\AbstractHelper;

/**
 * Class RcmUserMessageListHelper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserMessageListHelper extends AbstractHelper
{
    protected $findUserMessages;
    protected $getServerRequest;
    protected $getCurrentUserId;
    protected $renderUserMessages;

    /**
     * @param FindUserMessages   $findUserMessages
     * @param GetServerRequest   $getServerRequest
     * @param GetCurrentUserId   $getCurrentUserId
     * @param RenderUserMessages $renderUserMessages
     */
    public function __construct(
        FindUserMessages $findUserMessages,
        GetServerRequest $getServerRequest,
        GetCurrentUserId $getCurrentUserId,
        RenderUserMessages $renderUserMessages
    ) {
        $this->findUserMessages = $findUserMessages;
        $this->getServerRequest = $getServerRequest;
        $this->getCurrentUserId = $getCurrentUserId;
        $this->renderUserMessages = $renderUserMessages;
    }

    /**
     * @param null|string $source
     * @param null|       $level
     * @param null|bool   $showHasViewed
     * @param bool        $showDefaultMessage
     * @param null        $userId
     *
     * @return string
     */
    public function __invoke(
        $source = null,
        $level = null,
        $showHasViewed = false,
        $showDefaultMessage = false,
        $userId = null
    ) {
        $serverRequest = $this->getServerRequest->__invoke();
        if (empty($userId)) {
            $userId = $this->getCurrentUserId->__invoke(
                $serverRequest
            );
        }

        if (empty($userId)) {
            return '';
        }

        $messages = $this->findUserMessages->__invoke(
            $userId,
            $source,
            $level,
            $showHasViewed
        );

        return $this->renderUserMessages->__invoke(
            $serverRequest,
            $messages,
            [
                RenderUserMessages::OPTION_USER_ID => $userId,
                'show-default-message' => $showDefaultMessage
            ]
        );
    }
}
