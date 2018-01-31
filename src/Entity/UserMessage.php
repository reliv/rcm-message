<?php

namespace RcmMessage\Entity;

use Doctrine\ORM\Mapping as ORM;
use Reliv\RcmApiLib\Model\AbstractApiModel;

/**
 * @category  Reliv
 * @package   RcmMessage\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="RcmMessage\Repository\UserMessage")
 * @ORM\Table (
 *     name="rcm_message_user_message"
 * )
 */
class UserMessage extends AbstractApiModel implements UserMessageInterface
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateViewed = null;

    /**
     * @var string $id
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $userId;

    /**
     * @var Message
     * @ORM\ManyToOne(targetEntity="Message", fetch="EAGER", cascade={"persist"}))
     * @ORM\JoinColumn(name="messageId", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $message;

    /**
     * @param $userId
     */
    public function __construct($userId = null)
    {
        $this->userId = $userId;
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
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $userId
     *
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param bool $viewed
     *
     * @return void
     */
    public function setViewed($viewed = true)
    {
        if ($viewed) {
            $date = new \DateTime();
            $this->setDateViewed($date);

            return;
        }

        $this->dateViewed = null;
    }

    /**
     * @return bool
     */
    public function hasViewed()
    {
        return !empty($this->dateViewed);
    }

    /**
     * @return \DateTime
     */
    public function getDateViewed()
    {
        return $this->dateViewed;
    }

    /**
     * @param $dateViewed
     *
     * @return void
     */
    public function setDateViewed($dateViewed)
    {
        if (!empty($this->dateViewed)) {
            return;
        }
        $this->dateViewed = $dateViewed;
    }

    /**
     * from ISO8601 string
     *
     * @param $dateViewed
     *
     * @return void
     */
    public function setDateViewedString($dateViewed)
    {
        $date = \DateTime::createFromFormat(\DateTime::ISO8601, $dateViewed);

        $this->setDateViewed($date);
    }

    /**
     * @return null|string
     */
    public function getDateViewedString()
    {
        if (empty($this->dateViewed)) {
            return null;
        }

        return $this->dateViewed->format(\DateTime::ISO8601);
    }

    /**
     * @param array $ignore
     *
     * @return array
     */
    public function toArray(
        $ignore = []
    ) {
        $array = get_object_vars($this);

        $array['dateViewed'] = $this->getDateViewedString();
        $array['viewed'] = $this->hasViewed();

        return $array;
    }
}
