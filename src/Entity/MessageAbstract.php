<?php

namespace RcmMessage\Entity;

use Reliv\RcmApiLib\Model\AbstractApiModel;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class MessageAbstract extends AbstractApiModel
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $level
     */
    protected $level = MessageInterface::LEVEL_DEFAULT;

    /**
     * @var string $subject
     */
    protected $subject = '';

    /**
     * @var string $message
     */
    protected $message = '';

    /**
     * @var string $source
     */
    protected $source = null;

    /**
     * @var \DateTime
     */
    protected $dateCreated = null;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param int    $level
     * @param string $subject
     * @param string $message
     * @param null   $source
     * @param array  $properties
     */
    public function __construct(
        $level = MessageInterface::LEVEL_DEFAULT,
        $subject = '',
        $message = '',
        $source = null,
        $properties = []
    ) {
        $this->setLevel($level);
        $this->setSubject($subject);
        $this->setMessage($message);
        $this->setSource($source);
        $this->setProperties($properties);
        $this->setDateCreated(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return void
     */
    public function setLevel($level)
    {
        if (empty($level)) {
            $level = MessageInterface::LEVEL_DEFAULT;
        }

        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param $source
     *
     * @return void
     */
    public function setSource($source)
    {
        if (empty($source)) {
            $source = null;
        }
        $this->source = $source;
    }

    /**
     * @return \Datetime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     *
     * @return void
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * from ISO8601 string
     *
     * @param $dateCreated
     *
     * @return void
     */
    public function setDateCreatedString($dateCreated)
    {
        $date = \DateTime::createFromFormat(\DateTime::ISO8601, $dateCreated);

        $this->setDateCreated($date);
    }

    /**
     * @return null|string
     */
    public function getDateCreatedString()
    {
        if (empty($this->dateCreated)) {
            return null;
        }

        return $this->dateCreated->format(\DateTime::ISO8601);
    }

    /**
     * @param array $properties
     *
     * @return void
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public function findProperty($key, $default = null)
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }

        return $default;
    }

    /**
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        $array = get_object_vars($this);

        $array['dateCreated'] = $this->getDateCreatedString();

        return $array;
    }
}
