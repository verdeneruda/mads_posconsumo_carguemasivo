<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Schedule.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="schedule")
 * @ORM\Entity
 * @Gedmo\Loggable
 * @ApiResource(collectionOperations={}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "normalization_context"={"groups"={"schedule"}}
 * })
 */
class Schedule
{

	/**
	 *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * List of days associated to the schedule.
     *
     * @var string[]
     * @ORM\Column(type="simple_array")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"schedule"})
     */
    private $days = array();

    /**
     * Opening time.
     *
     * @var \DateTime|null
     * @ORM\Column(name="opening_time", type="time")
     * @Gedmo\Versioned
     * @Groups({"schedule"})
     */
    private $openingTime;

    /**
     * Closing time.
     *
     * @var \DateTime|null
     * @ORM\Column(name="closing_time", type="time")
     * @Gedmo\Versioned
     * @Groups({"schedule"})
     */
    private $closingTime;

    /**
     * Indicate if the is continuous day.
     *
     * @var bool
     * @ORM\Column(name="continuous_day", type="boolean")
     * @Gedmo\Versioned
     * @Groups({"schedule"})
     */
    private $continuousDay;

    /**
     * The creation date of the campaign.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"schedule"})
     */
    private $createdAt;

     /**
     * Constructor of the Schedule class.
     * (Initialize some fields).
     */
    public function __construct()
    {
        $this->days = array('LUN', 'MAR', 'MIE', 'JUE', 'VIE');
        $this->openingTime = new \DateTime('08:00');
        $this->closingTime = new \DateTime('17:00');
        $this->continuousDay = true;
    }

    public function __tostring() {
        return $this->getScheduleString();
    }

    /**
     * @Groups({"simple_schedule"})
     */
    public function getScheduleString()
    {
        $days = implode(', ', $this->days);
        $openingTime = $this->openingTime->format('h:ia');
        $closingTime = $this->closingTime->format('h:ia');
        $continuousDay = $this->continuousDay ? ' Jorn. continua' : '';
        return $days . ' ' . $openingTime . ' - ' . $closingTime . $continuousDay;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set days
     *
     * @param array $days
     *
     * @return Schedule
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set openingTime
     *
     * @param \DateTime $openingTime
     *
     * @return Schedule
     */
    public function setOpeningTime($openingTime)
    {
        $this->openingTime = $openingTime;

        return $this;
    }

    /**
     * Get openingTime
     *
     * @return \DateTime
     */
    public function getOpeningTime()
    {
        return $this->openingTime;
    }

    /**
     * Set closingTime
     *
     * @param \DateTime $closingTime
     *
     * @return Schedule
     */
    public function setClosingTime($closingTime)
    {
        $this->closingTime = $closingTime;

        return $this;
    }

    /**
     * Get closingTime
     *
     * @return \DateTime
     */
    public function getClosingTime()
    {
        return $this->closingTime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Schedule
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set continuousDay
     *
     * @param boolean $continuousDay
     *
     * @return Schedule
     */
    public function setContinuousDay($continuousDay)
    {
        $this->continuousDay = $continuousDay;

        return $this;
    }

    /**
     * Get continuousDay
     *
     * @return boolean
     */
    public function getContinuousDay()
    {
        return $this->continuousDay;
    }
}
