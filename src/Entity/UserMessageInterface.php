<?php

namespace RcmMessage\Entity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface UserMessageInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     *
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getUserId();

    /**
     * @param $userId
     *
     * @return void
     */
    public function setUserId($userId);

    /**
     * @return Message
     */
    public function getMessage();

    /**
     * @param Message $message
     *
     * @return void
     */
    public function setMessage($message);

    /**
     * @param bool $viewed
     *
     * @return void
     */
    public function setViewed($viewed = true);

    /**
     * @return bool
     */
    public function hasViewed();

    /**
     * @return \DateTime
     */
    public function getDateViewed();

    /**
     * @param $dateViewed
     *
     * @return void
     */
    public function setDateViewed($dateViewed);

    /**
     * from ISO8601 string
     *
     * @param $dateViewed
     *
     * @return void
     */
    public function setDateViewedString($dateViewed);

    /**
     * @return null|string
     */
    public function getDateViewedString();

    /**
     * @param array $ignore
     *
     * @return array
     */
    public function toArray(
        $ignore = []
    );
}
