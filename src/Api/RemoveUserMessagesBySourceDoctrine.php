<?php

namespace RcmMessage\Api;

use Doctrine\ORM\EntityManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RemoveUserMessagesBySourceDoctrine implements RemoveUserMessagesBySource
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
     * @param $userId
     * @param $source
     *
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(
        $userId,
        $source
    ) {
        /** @var UserMe $userMessages */
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
        $this->entityMgr->flush([$message, $userMessage]);
    }
}
