<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Class EntityFilter.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="entity_filter")
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class EntityFilter
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
     * The entity field name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     */
    private $entity_field_name;

    /**
     * The filter values
     *
     * @var object
     * @ORM\Column(type="object")
     * @Gedmo\Versioned
     */
    private $entity_field_values;

    /**
     * The filter type.
     *
     * @var string
     * @ORM\Column(name="filter_type", type="FiltersTypes", nullable=false)
     * @DoctrineAssert\Enum(entity="MADSPosconsumosBundle\Enum\FiltersTypes")     
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     */
    private $filter_type;

    /**
     * The entity filter report.
     *
     * @var Report
     * @ORM\ManyToOne(targetEntity="Report", inversedBy="entity_filters")
     * @ORM\JoinColumn(name="report_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $report;
    
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
     * Set entityFieldName
     *
     * @param string $entityFieldName
     *
     * @return EntityFilter
     */
    public function setEntityFieldName($entityFieldName)
    {
        $this->entity_field_name = $entityFieldName;

        return $this;
    }

    /**
     * Get entityFieldName
     *
     * @return string
     */
    public function getEntityFieldName()
    {
        return $this->entity_field_name;
    }

    /**
     * Set entityFieldValues
     *
     * @param object $entityFieldValues
     *
     * @return EntityFilter
     */
    public function setEntityFieldValues($entityFieldValues)
    {
        $this->entity_field_values = $entityFieldValues;

        return $this;
    }

    /**
     * Get entityFieldValues
     *
     * @return object
     */
    public function getEntityFieldValues()
    {
        return $this->entity_field_values;
    }

    /**
     * Set filterType
     *
     * @param string $filterType
     *
     * @return EntityFilter
     */
    public function setFilterType($filterType)
    {
        $this->filter_type = $filterType;

        return $this;
    }

    /**
     * Get filterType
     *
     * @return string
     */
    public function getFilterType()
    {
        return $this->filter_type;
    }

    /**
     * Set report
     *
     * @param \MADSPosconsumosBundle\Entity\Report $report
     *
     * @return EntityFilter
     */
    public function setReport(\MADSPosconsumosBundle\Entity\Report $report = null)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return \MADSPosconsumosBundle\Entity\Report
     */
    public function getReport()
    {
        return $this->report;
    }
}
