<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Fluoresce\ValidateEmbedded\Constraints as Fluoresce;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use MADSPosconsumosBundle\Model\Point;

/**
 * Class CollectionPoint.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="collection_point")
 * @ORM\Entity
 * @Gedmo\Loggable
 * @ApiResource(collectionOperations={"get"={"method"="GET"}}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "pagination_enabled"=true,
 *     "normalization_context"={"groups"={"collection_point", "simple_waste", "user_program", "simple_schedule", "simple_campaign"}},
 *     "filters"={"collection_points.search_filter", "collection_points.boolean_filter", "collection_points.geopoint_filter", "collection_points.date_filter"}
 * })
 */
class CollectionPoint
{

	/**
	 *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"collection_point"})
     */
    protected $id = null;

    /**
     * The collection point name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank(groups={"collection_point_type", "route_point_type"})
     * @Groups({"collection_point", "simple_collection_point"})
     */
    private $name;

    /**
     * The description of the collection point.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $description;

    /**
     * Type of collection point
     * 
     * @var CollectionPointsTypes
     * @ORM\Column(name="collection_point_type", type="CollectionPointsTypes", nullable=false)
     * @DoctrineAssert\Enum(entity="MADSPosconsumosBundle\Enum\CollectionPointsTypes")
     * @Groups({"collection_point"})
     */
    private $collectionPointType;

    /**
     * Schedule in the collection point.
     *
     * @var Schedule[]
     * @ORM\ManyToMany(targetEntity="Schedule", cascade={"persist"})
     * @ORM\JoinTable(name="collection_point_schedule")
     * @Assert\Count(min = "1", groups={"collection_point_type"})
     * @Assert\Count(max = "0", groups={"route_point_type"})
     * @Fluoresce\Validate(groups={"collection_point_type"});
     * @Groups({"simple_schedule"})
     **/
    private $schedules;

    /**
     * Pickup Datetime if Route Point
     * 
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThan("today", groups={"route_point_type"})
     * @Assert\NotBlank(groups={"route_point_type"})
     * @Assert\Blank(groups={"collection_point_type"})
     * @Groups({"collection_point"})
     */
    private $datetime;
    
    /**
     * The collection point address line 1.
     *
     * @var string
     * @ORM\Column(name="address_line1", type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank(groups={"collection_point_type", "route_point_type"})
     * @Groups({"collection_point", "simple_collection_point"})
     */
    private $addressLine1;

    /**
     * The collection point address line 2.
     *
     * @var string
     * @ORM\Column(name="address_line2", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $addressLine2;

    /**
     * The collection point streetNumber.
     *
     * @var string
     * @ORM\Column(name="street_number", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $streetNumber;

    /**
     * The collection point route.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $route;

    /**
     * The collection point locality.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $locality;

    /**
     * The collection point subLocality.
     *
     * @var string
     * @ORM\Column(name="sub_locality", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $subLocality;

    /**
     * The collection point administrative level 1.
     *
     * @var string
     * @ORM\Column(name="administrative_level1", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $administrativeLevel1;

    /**
     * The collection point administrative level 2.
     *
     * @var string
     * @ORM\Column(name="administrative_level2", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $administrativeLevel2;

    /**
     * The collection point administrative level 3.
     *
     * @var string
     * @ORM\Column(name="administrative_level3", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $administrativeLevel3;

    /**
     * The collection point country.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $country;

    /**
     * The geo point of the collection point.
     *
     * @ORM\Column(type="point")
     * @Fluoresce\Validate(groups={"collection_point_type", "route_point_type"});
     */
    private $point;

    /**
     * The collection point contact Users.
     *
     * @var User[]
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="collection_point_user")
     * @Assert\Count(min = "1", groups={"collection_point_type", "route_point_type"})
     * @Groups({"user_program"})
     */
    private $users;

    /**
     * Waste in the collection point.
     *
     * @var Waste[]
     * @ORM\ManyToMany(targetEntity="Waste")
     * @ORM\JoinTable(name="collection_point_waste")
     * @Assert\Count(min = "1", groups={"collection_point_type", "route_point_type"})
     * @Groups({"simple_waste"})
     **/
    private $waste;

    /**
     * Campaigns
     *
     * @var Campaign[]
     * @ORM\ManyToMany(targetEntity="Campaign", mappedBy="collectionPoints")
     * @Groups({"simple_campaign"})
     */
    private $campaigns;

    /**
     * Indicate if the collection point is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     * @Groups({"collection_point"})
     */
    private $enabled = true;

    /**
     * The creation date of the collection point.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"collection_point"})
     */
    private $createdAt;

    /** {@inheritdoc} */
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {   
        $this->waste = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->schedules  = new ArrayCollection();
        $this->campaigns  = new ArrayCollection();
        $this->point = new Point(-74.29733299999998, 4.570868);

        $this->datetime = new \DateTime();
        
        // $schedule = new Schedule();
        // $this->addSchedule($schedule);
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
     * Set name
     *
     * @param string $name
     *
     * @return CollectionPoint
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CollectionPoint
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set addressLine1
     *
     * @param string $addressLine1
     *
     * @return CollectionPoint
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    /**
     * Get addressLine1
     *
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * Set addressLine2
     *
     * @param string $addressLine2
     *
     * @return CollectionPoint
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    /**
     * Get addressLine2
     *
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * Set point
     *
     * @param point $point
     *
     * @return CollectionPoint
     */
    public function setPoint(Point $point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return point
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return CollectionPoint
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CollectionPoint
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
     * Add schedule
     *
     * @param \MADSPosconsumosBundle\Entity\Schedule $schedule
     *
     * @return CollectionPoint
     */
    public function addSchedule(\MADSPosconsumosBundle\Entity\Schedule $schedule)
    {
        $this->schedules[] = $schedule;

        return $this;
    }

    /**
     * Remove schedule
     *
     * @param \MADSPosconsumosBundle\Entity\Schedule $schedule
     */
    public function removeSchedule(\MADSPosconsumosBundle\Entity\Schedule $schedule)
    {
        $this->schedules->removeElement($schedule);
    }

    /**
     * Get schedules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * Add user
     *
     * @param \MADSPosconsumosBundle\Entity\User $user
     *
     * @return CollectionPoint
     */
    public function addUser(\MADSPosconsumosBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \MADSPosconsumosBundle\Entity\User $user
     */
    public function removeUser(\MADSPosconsumosBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return CollectionPoint
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return CollectionPoint
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return CollectionPoint
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set subLocality
     *
     * @param string $subLocality
     *
     * @return CollectionPoint
     */
    public function setSubLocality($subLocality)
    {
        $this->subLocality = $subLocality;

        return $this;
    }

    /**
     * Get subLocality
     *
     * @return string
     */
    public function getSubLocality()
    {
        return $this->subLocality;
    }

    /**
     * Set administrativeLevel1
     *
     * @param string $administrativeLevel1
     *
     * @return CollectionPoint
     */
    public function setAdministrativeLevel1($administrativeLevel1)
    {
        $this->administrativeLevel1 = $administrativeLevel1;

        return $this;
    }

    /**
     * Get administrativeLevel1
     *
     * @return string
     */
    public function getAdministrativeLevel1()
    {
        return $this->administrativeLevel1;
    }

    /**
     * Set administrativeLevel2
     *
     * @param string $administrativeLevel2
     *
     * @return CollectionPoint
     */
    public function setAdministrativeLevel2($administrativeLevel2)
    {
        $this->administrativeLevel2 = $administrativeLevel2;

        return $this;
    }

    /**
     * Get administrativeLevel2
     *
     * @return string
     */
    public function getAdministrativeLevel2()
    {
        return $this->administrativeLevel2;
    }

    /**
     * Set administrativeLevel3
     *
     * @param string $administrativeLevel3
     *
     * @return CollectionPoint
     */
    public function setAdministrativeLevel3($administrativeLevel3)
    {
        $this->administrativeLevel3 = $administrativeLevel3;

        return $this;
    }

    /**
     * Get administrativeLevel3
     *
     * @return string
     */
    public function getAdministrativeLevel3()
    {
        return $this->administrativeLevel3;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return CollectionPoint
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add waste
     *
     * @param \MADSPosconsumosBundle\Entity\Waste $waste
     *
     * @return CollectionPoint
     */
    public function addWaste(\MADSPosconsumosBundle\Entity\Waste $waste)
    {
        $this->waste[] = $waste;

        return $this;
    }

    /**
     * Remove waste
     *
     * @param \MADSPosconsumosBundle\Entity\Waste $waste
     */
    public function removeWaste(\MADSPosconsumosBundle\Entity\Waste $waste)
    {
        $this->waste->removeElement($waste);
    }

    /**
     * Get waste
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWaste()
    {
        return $this->waste;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return CollectionPoint
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Add campaign
     *
     * @param \MADSPosconsumosBundle\Entity\Campaign $campaign
     *
     * @return CollectionPoint
     */
    public function addCampaign(\MADSPosconsumosBundle\Entity\Campaign $campaign)
    {
        $this->campaigns[] = $campaign;

        return $this;
    }

    /**
     * Remove campaign
     *
     * @param \MADSPosconsumosBundle\Entity\Campaign $campaign
     */
    public function removeCampaign(\MADSPosconsumosBundle\Entity\Campaign $campaign)
    {
        $this->campaigns->removeElement($campaign);
    }

    /**
     * Get campaigns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * Set collectionPointType
     *
     * @param CollectionPointsTypes $collectionPointType
     *
     * @return CollectionPoint
     */
    public function setCollectionPointType($collectionPointType)
    {
        $this->collectionPointType = $collectionPointType;

        return $this;
    }

    /**
     * Get collectionPointType
     *
     * @return CollectionPointsTypes
     */
    public function getCollectionPointType()
    {
        return $this->collectionPointType;
    }

    /**
     * @Groups({"collection_point", "simple_collection_point"})
     */
    public function getGeoPoint()
    {
        return $this->point == null ? null : array('latitude' => $this->point->getLatitude(), 'longitude' => $this->point->getLongitude());
    }
}
