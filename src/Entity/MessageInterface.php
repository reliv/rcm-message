<?php

namespace RcmMessage\Entity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface MessageInterface
{
    const LEVEL_DEFAULT = 16;

    const LEVEL_CRITICAL = 2;
    const LEVEL_ERROR = 4;
    const LEVEL_WARNING = 8;
    const LEVEL_INFO = 16;
    const LEVEL_SUCCESS = 32;

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
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level
     *
     * @return void
     */
    public function setLevel($level);

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param $message
     *
     * @return void
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param $source
     *
     * @return void
     */
    public function setSource($source);

    /**
     * @return \Datetime
     */
    public function getDateCreated();

    /**
     * @param \DateTime $dateCreated
     *
     * @return void
     */
    public function setDateCreated($dateCreated);

    /**
     * from ISO8601 string
     *
     * @param $dateCreated
     *
     * @return void
     */
    public function setDateCreatedString($dateCreated);

    /**
     * @return null|string
     */
    public function getDateCreatedString();

    /**
     * @param array $properties
     *
     * @return void
     */
    public function setProperties(array $properties);

    /**
     * @return array
     */
    public function getProperties();

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public function findProperty($key, $default = null);

    /**
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = []);
}
