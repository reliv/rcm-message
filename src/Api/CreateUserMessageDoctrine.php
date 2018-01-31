<?php

namespace RcmMessage\Api;

use Doctrine\ORM\EntityManager;
use RcmMessage\Entity\Message;
use RcmMessage\Entity\UserMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateUserMessageDoctrine implements CreateUserMessage
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
     * @param string $userId
     * @param string $subject
     * @param string $body
     * @param string $level
     * @param string $source
     * @param array  $properties
     *
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(
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
}
