<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use MADSPosconsumosBundle\Enum\EntitiesTypes;

/**
 * Class Report.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="report")
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Report
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
     * The report name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * The report entity class name.
     *
     * @var string
     * @ORM\Column(name="entity_type", type="EntitiesTypes", nullable=false)
     * @DoctrineAssert\Enum(entity="MADSPosconsumosBundle\Enum\EntitiesTypes")     
     * @Gedmo\Versioned
     */
    private $entity_type = EntitiesTypes::CAMPAIGNS;

    /**
     * The report entity field names.
     *
     * @var array
     * @ORM\Column(name="entity_field_names", type="array")
     * @Gedmo\Versioned
     */
    private $entity_field_names = array();


    /**
     * The report entity filters.
     *
     * @var EntityFilter[]
     * @ORM\OneToMany(targetEntity="EntityFilter", mappedBy="report", cascade={"all"}, orphanRemoval=true)
     */
    private $entity_filters;

    /**
     * The report doctrine query.
     *
     * @var string
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $dql;

    /**
     * The report query parameters
     *
     * @var object
     * @ORM\Column(type="object")
     * @Gedmo\Versioned
     */
    private $query_parameters;

    /**
     * The report program.
     *
     * @var Program
     *
     * @ORM\ManyToOne(targetEntity="Program")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $program;

    /**
     * The creation date of the report.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;


    /** {@inheritdoc} */
    public function __toString()
    {
        return $this->getName();
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
     * @return Report
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Report
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
     * Set dql
     *
     * @param string $dql
     *
     * @return Report
     */
    public function setDql($dql)
    {
        $this->dql = $dql;

        return $this;
    }

    /**
     * Get dql
     *
     * @return string
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * Set entityType
     *
     * @param \EntitiesType $entityType
     *
     * @return Report
     */
    public function setEntityType($entityType)
    {
        $this->entity_type = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return \EntitiesType
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * Set entityFieldNames
     *
     * @param array $entityFieldNames
     *
     * @return Report
     */
    public function setEntityFieldNames($entityFieldNames)
    {
        $this->entity_field_names = $entityFieldNames;

        return $this;
    }

    /**
     * Get entityFieldNames
     *
     * @return array
     */
    public function getEntityFieldNames()
    {
        return $this->entity_field_names;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entity_filters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add entityFilter
     *
     * @param \MADSPosconsumosBundle\Entity\EntityFilter $entityFilter
     *
     * @return Report
     */
    public function addEntityFilter(\MADSPosconsumosBundle\Entity\EntityFilter $entityFilter)
    {
        $entityFilter->setReport($this);

        $this->entity_filters[] = $entityFilter;

        return $this;
    }

    /**
     * Remove entityFilter
     *
     * @param \MADSPosconsumosBundle\Entity\EntityFilter $entityFilter
     */
    public function removeEntityFilter(\MADSPosconsumosBundle\Entity\EntityFilter $entityFilter)
    {
        $entityFilter->setReport(null);

        $this->entity_filters->removeElement($entityFilter);
    }

    /**
     * Get entityFilters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntityFilters()
    {
        return $this->entity_filters;
    }

    /**
     * Set queryParameters
     *
     * @param \stdClass $queryParameters
     *
     * @return Report
     */
    public function setQueryParameters($queryParameters)
    {
        $this->query_parameters = $queryParameters;

        return $this;
    }

    /**
     * Get queryParameters
     *
     * @return \stdClass
     */
    public function getQueryParameters()
    {
        return $this->query_parameters;
    }

    /**
     * Set program
     *
     * @param \MADSPosconsumosBundle\Entity\Program $program
     *
     * @return Report
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
}
