<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use MADSPosconsumosBundle\Enum\CampaignsTypes;

/**
 * Class Campaign.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="campaign")
 * @ORM\Entity
 * @Vich\Uploadable
 * @Gedmo\Loggable
 * @ApiResource(collectionOperations={"get"={"method"="GET"}}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "normalization_context"={"groups"={"campaign", "user_program", "simple_collection_point"}},
 *     "filters"={"campaigns.boolean_filter", "campaigns.date_filter"}
 * })
 */
class Campaign
{

	/**
	 *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"campaign"})
     */
    protected $id = null;

    /**
     * The campaign name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @Groups({"campaign", "simple_campaign"})
     */
    private $name;

    /**
     * The description of the campaign.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"campaign"})
     */
    private $description;

    /**
     * It only stores the name of the image associated with the campaign.
     *
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     * @Groups({"campaign", "simple_campaign"})
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the campaign.
     *
     * @Vich\UploadableField(mapping="campaign_images", fileNameProperty="image")
     * @Assert\NotBlank()
     * @Assert\Image(
     *     mimeTypes = "image/jpeg",
     *     allowLandscape = false,
     *     allowPortrait = true,
     *     minWidth = 1242,
     *     maxWidth = 2484,
     *     minHeight = 2208,
     *     maxHeight = 4416,
     *     
     * )
     * @var File
     */
    private $imageFile;

    /**
     * The opening date of the campaign.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="campaign_start")
     * @Gedmo\Versioned
     * @Assert\GreaterThan("today")
     * @Assert\NotBlank()
     * @Groups({"campaign", "simple_campaign"})
     */
    private $campaignStart;

    /**
     * The ending date of the campaign.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="campaign_end")
     * @Gedmo\Versioned
     * @Assert\GreaterThan("today")
     * @Assert\NotBlank()
     * @Groups({"campaign", "simple_campaign"})
     */
    private $campaignEnd;

    /**
     * The publish date of the campaign.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="publish_at")
     * @Gedmo\Versioned
     * @Assert\GreaterThan("today")
     * @Assert\NotBlank()
     * @Groups({"campaign"})
     */
    private $publishAt;

    /**
     * Type of campaign
     * 
     * @var CampaignsTypes
     * @ORM\Column(name="campaign_type", type="CampaignsTypes", nullable=false)
     * @DoctrineAssert\Enum(entity="MADSPosconsumosBundle\Enum\CampaignsTypes")     
     * @Groups({"campaign"})
     */
    private $campaignType;

    /**
     * Collection Points in the campaign.
     *
     * @var CollectionPoint[]
     * @ORM\ManyToMany(targetEntity="CollectionPoint", inversedBy="campaigns")
     * @ORM\JoinTable(name="campaign_collection_point")
     * @Assert\Count(min = "1")
     * @Groups({"simple_collection_point"})
     **/
    private $collectionPoints;

    /**
     * The campaign contact Users.
     *
     * @var User[]
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="campaign_user")
     * @Assert\Count(min = "1")
     * @Groups({"user_program"})
     */
    private $users;

    /**
     * Indicate if the campaign is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     * @Groups({"campaign"})
     */
    private $enabled = true;

    /**
     * The creation date of the campaign.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"campaign"})
     */
    private $createdAt;

    /** {@inheritdoc} */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Constructor of the Campaign class.
     * (Initialize some fields).
     */
    public function __construct()
    {
        $this->campaignStart = new \DateTime();
        $this->campaignEnd = new \DateTime();
        $this->publishAt = new \DateTime();
        $this->campaignType = CampaignsTypes::COLLECTION_POINTS;
        $this->collectionPoints = new ArrayCollection();
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
     * @return Campaign
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
     * @return Campaign
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
     * Set image
     *
     * @param string $image
     *
     * @return Campaign
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param File $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set campaignStart
     *
     * @param \DateTime $campaignStart
     *
     * @return Campaign
     */
    public function setCampaignStart($campaignStart)
    {
        $this->campaignStart = $campaignStart;

        return $this;
    }

    /**
     * Get campaignStart
     *
     * @return \DateTime
     */
    public function getCampaignStart()
    {
        return $this->campaignStart;
    }

    /**
     * Set campaignEnd
     *
     * @param \DateTime $campaignEnd
     *
     * @return Campaign
     */
    public function setCampaignEnd($campaignEnd)
    {
        $this->campaignEnd = $campaignEnd;

        return $this;
    }

    /**
     * Get campaignEnd
     *
     * @return \DateTime
     */
    public function getCampaignEnd()
    {
        return $this->campaignEnd;
    }

    /**
     * Set publishAt
     *
     * @param \DateTime $publishAt
     *
     * @return Campaign
     */
    public function setPublishAt($publishAt)
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    /**
     * Get publishAt
     *
     * @return \DateTime
     */
    public function getPublishAt()
    {
        return $this->publishAt;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Campaign
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
     * @return Campaign
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
     * Add collectionPoint
     *
     * @param \MADSPosconsumosBundle\Entity\CollectionPoint $collectionPoint
     *
     * @return Campaign
     */
    public function addCollectionPoint(\MADSPosconsumosBundle\Entity\CollectionPoint $collectionPoint)
    {
        $this->collectionPoints[] = $collectionPoint;

        return $this;
    }

    /**
     * Remove collectionPoint
     *
     * @param \MADSPosconsumosBundle\Entity\CollectionPoint $collectionPoint
     */
    public function removeCollectionPoint(\MADSPosconsumosBundle\Entity\CollectionPoint $collectionPoint)
    {
        $this->collectionPoints->removeElement($collectionPoint);
    }

    /**
     * Get collectionPoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollectionPoints()
    {
        return $this->collectionPoints;
    }

    /**
     * Add user
     *
     * @param \MADSPosconsumosBundle\Entity\User $user
     *
     * @return Campaign
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
     * Set campaignType
     *
     * @param CampaignsTypes $campaignType
     *
     * @return Campaign
     */
    public function setCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;

        return $this;
    }

    /**
     * Get campaignType
     *
     * @return CampaignsTypes
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }
}
