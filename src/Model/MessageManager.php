<?php

namespace RcmMessage\Model;

use Doctrine\ORM\EntityManager;
use RcmMessage\Entity\Message;
use RcmMessage\Entity\UserMessage;

/**
 * @deprecated Use RcmMessage\Api\
 *
 * @category  Reliv
 * @package   RcmMessage\Model
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class MessageManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityMgr;

    /**
     * @param EntityManager $entityMgr
     */
    public function __construct(EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * @deprecated
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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUserMessage(
        $userId,
        $subject,
        $body,
        $level,
        $source,
        $properties = []
    ) {
        $message = new Message();
        $message->setSubject($subject);
        $message->setMessage($body);
        $message->setLevel($level);
        $message->setSource($source);
        $message->setProperties($properties);
        $this->entityMgr->persist($message);
        $this->entityMgr->flush($message);

        $userMessage = new UserMessage($userId);
        $userMessage->setMessage($message);
        $this->entityMgr->persist($userMessage);
        $this->entityMgr->flush($userMessage);
    }

    /**
     * Delete all messages with the given userId and source
     *
     * @param string $userId
     * @param string $source
     *
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeUserMessagesBySource($userId, $source)
    {
        $userMessages = $this->entityMgr
            ->getRepository(\RcmMessage\Entity\UserMessage::class)
            ->findBy(['userId' => $userId]);
        foreach ($userMessages as $userMessage) {
            $message = $userMessage->getMessage();
            if ($message->getSource() == $source) {
                $this->entityMgr->remove($message);
                $this->entityMgr->remove($userMessage);
            }
        }
        $this->entityMgr->flush();
    }
}
