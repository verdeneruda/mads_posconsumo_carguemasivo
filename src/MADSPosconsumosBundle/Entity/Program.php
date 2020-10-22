<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use MADSPosconsumosBundle\Model\Point;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Program.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="program")
 * @ORM\Entity
 * @Gedmo\Loggable
 * @ApiResource(collectionOperations={"get"={"method"="GET"}}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "normalization_context"={"groups"={"program", "simple_user"}},
 *     "filters"={"campaigns.boolean_filter", "campaigns.date_filter"}
 * })
 */
class Program
{

	/**
	 *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"program"})
     */
    protected $id = null;

    /**
     * The Program name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"program", "user_program"})
     */
    private $name;

    /**
     * The Program address line 1.
     *
     * @var string
     * @ORM\Column(name="address_line1", type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"program"})
     */
    private $addressLine1;

    /**
     * The Program address line 2.
     *
     * @var string
     * @ORM\Column(name="address_line2", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $addressLine2;

    /**
     * The Program streetNumber.
     *
     * @var string
     * @ORM\Column(name="street_number", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $streetNumber;

    /**
     * The Program route.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $route;

    /**
     * The Program locality.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $locality;

    /**
     * The Program subLocality.
     *
     * @var string
     * @ORM\Column(name="sub_locality", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $subLocality;

    /**
     * The Program administrative level 1.
     *
     * @var string
     * @ORM\Column(name="administrative_level1", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $administrativeLevel1;

    /**
     * The Program administrative level 2.
     *
     * @var string
     * @ORM\Column(name="administrative_level2", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $administrativeLevel2;

    /**
     * The Program administrative level 3.
     *
     * @var string
     * @ORM\Column(name="administrative_level3", type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $administrativeLevel3;

    /**
     * The Program country.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $country;

    /**
     * The geo point of the Program.
     *
     * @ORM\Column(type="point")
     * @Assert\NotBlank()
     */
    private $point;

    /**
     * The Program website.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $website;

    /**
     * The Program phone.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"program"})
     */
    private $phone;

    /**
     * The Program email.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $email;

    /**
     * Program users.
     * 
     * @var User[]
     * @ORM\OneToMany(targetEntity="User", mappedBy="program")
     * @Groups({"simple_user"})
     */
    private $users;

    /**
     * The creation date of the Program.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"program"})
     */
    private $createdAt;

    /**
     * Indicate if the program is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     * @Groups({"program"})
     */
    private $enabled = true;

    public function __tostring() {
        return $this->name;
    }    
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->point = new Point(-74.29733299999998, 4.570868);
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
     * @return Program
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
     * Set addressLine1
     *
     * @param string $addressLine1
     *
     * @return Program
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
     * @return Program
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
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return Program
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
     * @return Program
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
     * @return Program
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
     * @return Program
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
     * @return Program
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
     * @return Program
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
     * @return Program
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
     * @return Program
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
     * Set point
     *
     * @param point $point
     *
     * @return Program
     */
    public function setPoint($point)
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
     * Set website
     *
     * @param string $website
     *
     * @return Program
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Program
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Program
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Program
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
     * Add user
     *
     * @param \MADSPosconsumosBundle\Entity\User $user
     *
     * @return Program
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
     * @Groups({"program"})
     */
    public function getGeoPoint()
    {
        return $this->point == null ? null : array('latitude' => $this->point->getLatitude(), 'longitude' => $this->point->getLongitude());
    }
    

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Program
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
}
