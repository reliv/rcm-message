<?php

namespace RcmMessage\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use RcmMessage\Entity\UserMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindUserMessagesDoctrine implements FindUserMessages
{
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string      $userId    User Id to display message form
     * @param string|null $source    Source identifier or null to ignore
     * @param int|null    $level     Level (see UserMessage entity for static values) or null to ignore
     * @param bool|null   $hasViewed If user has viewed the message or null to ignore
     *
     * @return array
     */
    public function __invoke(
        $userId,
        $source = null,
        $level = null,
        $hasViewed = null
    ): array {
        $level = $this->getIntNullValue($level);
        $hasViewed = $this->getBoolNullValue($hasViewed);

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('userMessage');
        $queryBuilder->from(UserMessage::class, 'userMessage');
        $queryBuilder->join('userMessage.message', 'message');
        $queryBuilder->where('userMessage.userId = :userId');
        $queryBuilder->setParameter('userId', $userId);

        if (!empty($level)) {
            $queryBuilder->andWhere('message.level = :level');
            $queryBuilder->setParameter('level', $level);
        }

        if (!empty($source)) {
            $queryBuilder->andWhere('message.source = :source');
            $queryBuilder->setParameter('source', $source);
        }

        if ($hasViewed === true) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('userMessage.dateViewed'));
        }

        if ($hasViewed === false) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNull('userMessage.dateViewed'));
        }

        $queryBuilder->orderBy('message.level', 'DESC');
        $queryBuilder->orderBy('message.dateCreated', 'ASC');

        try {
            return $queryBuilder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return [];
        }
    }

    /**
     * getIntNullValue
     *
     * @param $var
     *
     * @return int|null
     */
    protected function getIntNullValue($var)
    {
        if (null === $var) {
            return null;
        }

        return (int)$var;
    }

    /**
     * getBoolNullValue
     *
     * @param $var
     *
     * @return bool|null
     */
    protected function getBoolNullValue($var)
    {
        if (null === $var || is_bool($var)) {
            return $var;
        }

        if ('' === $var) {
            return null;
        }

        return (bool)(int)$var;
    }
}
