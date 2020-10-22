<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Waste.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="waste")
 * @ORM\Entity
 * @Gedmo\Loggable
 * @Vich\Uploadable
 * @ApiResource(collectionOperations={}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "normalization_context"={"groups"={"waste", "simple_waste"}}
 * })
 */
class Waste
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
     * The name of the waste.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @Groups({"waste", "simple_waste"})
     */
    private $name;

    /**
     * The description of the waste.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"waste"})
     */
    private $description;

    /**
     * Waste Type parent
     */
    private $wasteTypeParent;

    /**
     * List of wasteTypes where the waste is
     *
     * @var WasteType[]
     * @ORM\ManyToMany(targetEntity="WasteType", inversedBy="waste")
     * @ORM\JoinTable(name="waste_type_waste")
     * @Assert\Count(min = "1")
     * @Groups({"waste", "simple_waste"})
     */
    private $wasteTypes;

    /**
     * Indicate if the waste+ is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $enabled = true;

    /**
     * It only stores the name of the image associated with the waste.
     *
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     * @Groups({"waste", "simple_waste"})
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the waste.
     *
     * @Vich\UploadableField(mapping="waste_images", fileNameProperty="image")
     * @Assert\NotBlank()
     * @Assert\Image(
     *     mimeTypes = "image/jpeg",
     *     allowLandscape = true,
     *     allowPortrait = false,
     *     minWidth = 640,
     *     maxWidth = 1280,
     *     minHeight = 480,
     *     maxHeight = 960
     *     
     * )
     * @var File
     */
    private $imageFile;

    /**
     * The creation date of the waste.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"waste"})
     */
    private $createdAt = null;

    /**
     * The waste program.
     *
     * @var Program
     * @ORM\ManyToOne(targetEntity="Program")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     * @Gedmo\Versioned
     * @Groups({"waste"})
     */
    private $program;

    /** {@inheritdoc} */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Constructor of the Waste class.
     * (Initialize some fields).
     */
    public function __construct()
    {
        $this->wasteTypes = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * @return Waste
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
     * @return Waste
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Waste
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
     * Set image
     *
     * @param string $image
     *
     * @return Waste
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Waste
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

    public function setWasteTypeParent($wasteTypeParent)
    {
        $this->wasteTypeParent = $wasteTypeParent;
    }    

    /**
     * 
     */
    public function getWasteTypeParent()
    {
        return count($this->wasteTypes) > 0 ? $this->wasteTypes->first()->getParent() : $this->wasteTypeParent;
    }

    /**
     * Add wasteType
     *
     * @param \MADSPosconsumosBundle\Entity\WasteType $wasteType
     *
     * @return Waste
     */
    public function addWasteType(\MADSPosconsumosBundle\Entity\WasteType $wasteType)
    {
        $this->wasteTypes[] = $wasteType;

        return $this;
    }

    /**
     * Remove wasteType
     *
     * @param \MADSPosconsumosBundle\Entity\WasteType $wasteType
     */
    public function removeWasteType(\MADSPosconsumosBundle\Entity\WasteType $wasteType)
    {
        $this->wasteTypes->removeElement($wasteType);
    }

    /**
     * Get wasteTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWasteTypes()
    {
        return $this->wasteTypes;
    }
    

    /**
     * Set program
     *
     * @param \MADSPosconsumosBundle\Entity\Program $program
     *
     * @return Waste
     */
    public function setProgram(\MADSPosconsumosBundle\Entity\Program $program = null)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \MADSPosconsumosBundle\Entity\Program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @Groups({"waste", "simple_waste"})
     */
    public function getWasteTypeClass()
    {   
        $wasteType = $this->getWasteTypeParent();
        return $wasteType !== null ? array("name" => $wasteType->getName(), "classname" => $wasteType->getClassname()) : null;
    }
}
