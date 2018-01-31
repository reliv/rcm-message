<?php

namespace RcmMessage\Api;

use Doctrine\ORM\EntityManager;
use RcmMessage\Entity\UserMessageInterface;

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
     * @param string $userId
     * @param string $source
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(
        $userId,
        $source
    ) {
        /** @var UserMessageInterface[] $userMessages */
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

        $this->entityMgr->flush($userMessages);
    }
}
