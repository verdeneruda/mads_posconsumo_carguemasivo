<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use MADSPosconsumosBundle\Enum\AppActionsTypes;
use MADSPosconsumosBundle\Enum\AppSectionsTypes;

use MADSPosconsumosBundle\Model\Point;

/**
 * Class AppActivity.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="app_activity")
 * @ORM\Entity
 * @ApiResource(collectionOperations={"post"={"method"="POST"}}, itemOperations={}, attributes={
 *     "normalization_context"={"groups"={"app_activity"}},
 *     "denormalization_context"={"groups"={"app_activity"}}
 * })
 */
class AppActivity
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
     * The instance identifier.
     *
     * @var string
     * @ORM\Column(name="instance_id", type="string")
     * @Assert\NotBlank()
     * @Groups({"app_activity"})
     */
    private $instanceID;

    /**
     * App Action
     * 
     * @ORM\Column(name="action", type="AppActionsTypes")
     * @DoctrineAssert\Enum(entity="MADSPosconsumosBundle\Enum\AppActionsTypes")
     * @Assert\NotBlank()
     */
    private $action;

    /**
     * App Section
     * 
     * @var AppSections
     * @ORM\Column(name="section", type="AppSectionsTypes", nullable=true)
     * @DoctrineAssert\Enum(entity="MADSPosconsumosBundle\Enum\AppSectionsTypes")
     */
    private $section;

    /**
     * Object identifier. 
     *
	 * @var int
     * @ORM\Column(name="object_id", type="integer", nullable=true)
     * @Groups({"app_activity"})
     */
    private $objectID;

    /**
     * The geo point of the activity.
     *
     * @ORM\Column(type="point")
     */
    private $point;

    /**
     * The creation date.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;    

    /**
     * Constructor of the Program class.
     * (Initialize some fields).
     */
    public function __construct()
    {
        $this->section = AppSectionsTypes::HOME;
        $this->action = AppActionsTypes::OPEN;
    }    


    /**
     * @Groups({"app_activity"})
     */
    public function setGeopoint($params = array('latitude' => 0, 'longitude' => 0))
    {
        $point = new Point($params['latitude'], $params['longitude']);
        $this->point = $point;
    }

    public function getGeopoint()
    {
        return array('latitude' => $this->point->getLatitude(), 'longitude' => $this->point->getLongitude());
    }

    /**
     * @Groups({"app_activity"})
     */
    public function setAppAction($params = AppActionsTypes::OPEN) {
        $this->action = $params;
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
     * Set instanceID
     *
     * @param string $instanceID
     *
     * @return AppActivity
     */
    public function setInstanceID($instanceID)
    {
        $this->instanceID = $instanceID;

        return $this;
    }

    /**
     * Get instanceID
     *
     * @return string
     */
    public function getInstanceID()
    {
        return $this->instanceID;
    }

    /**
     * Set action
     *
     * @param app_actions $action
     *
     * @return AppActivity
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return app_actions
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set section
     *
     * @param app_sections $section
     *
     * @return AppActivity
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return app_sections
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set objectID
     *
     * @param integer $objectID
     *
     * @return AppActivity
     */
    public function setObjectID($objectID)
    {
        $this->objectID = $objectID;

        return $this;
    }

    /**
     * Get objectID
     *
     * @return integer
     */
    public function getObjectID()
    {
        return $this->objectID;
    }

    /**
     * Set point
     *
     * @param point $point
     *
     * @return AppActivity
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AppActivity
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
}
