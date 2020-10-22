<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class WasteType.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="waste_type")
 * @ORM\Entity
 * @Gedmo\Loggable
 * @ApiResource(collectionOperations={"get"={"method"="GET"}}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "normalization_context"={"groups"={"waste_type"}}
 * })
 */
class WasteType
{

	/**
	 *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"waste_type"})
     */
    protected $id = null;

    /**
     * The WasteType name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @Groups({"waste_type", "simple_waste"})
     */
    private $name;

    /**
     * The description of the WasteType.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"waste_type"})
     */
    private $description;

    /**
     * The WasteType parent.
     *
     * @var WasteType
     * @ORM\ManyToOne(targetEntity="WasteType")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"waste_type"})
     **/
    private $parent;

    /**
     * The WasteType classname.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Groups({"waste_type"})
     */
    private $classname;

    /**
     * Waste in the WasteType.
     *
     * @var Waste[]
     * @ORM\ManyToMany(targetEntity="Waste", mappedBy="wasteTypes")
     **/
    private $waste;

    /**
     * Indicate if the WasteType is enabled.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     * @Groups({"waste_type"})
     */
    private $enabled = true;

    /**
     * The creation date of the WasteType.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"waste_type"})
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
        $this->waste = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return WasteType
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
     * @return WasteType
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
     * Set classname
     *
     * @param string $classname
     *
     * @return WasteType
     */
    public function setClassname($classname)
    {
        $this->classname = $classname;

        return $this;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return WasteType
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
     * @return WasteType
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
     * Set parent
     *
     * @param \MADSPosconsumosBundle\Entity\WasteType $parent
     *
     * @return WasteType
     */
    public function setParent(\MADSPosconsumosBundle\Entity\WasteType $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \MADSPosconsumosBundle\Entity\WasteType
     */
    public function getParent()
    {
        return $this->parent;
    }
        

    /**
     * Add waste
     *
     * @param \MADSPosconsumosBundle\Entity\Waste $waste
     *
     * @return WasteType
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
}
