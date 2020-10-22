<?php

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="MADSPosconsumosBundle\Repository\UserRepository")
 * @UniqueEntity("email")
 * @Gedmo\Loggable
 * @ApiResource(collectionOperations={}, itemOperations={"get"={"method"="GET"}}, attributes={
 *     "normalization_context"={"groups"={"user", "user_program"}}
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The user full name.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"user", "user_program", "simple_user"})
     */
    private $fullname;

    /**
     * The user phone.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"user"})
     */
    private $phone;

    /**
     * The user program.
     *
     * @var Program
     * @ORM\ManyToOne(targetEntity="Program", inversedBy="users")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     * @Gedmo\Versioned
     * @Groups({"user_program"})
     */
    private $program;

    /**
     * The user position.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Groups({"user", "user_program", "simple_user"})
     */
    private $position;

    /**
     * The creation date of the user.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"user"})
     */
    private $createdAt;


    public function __toString()
    {
        return $this->program->getName() . " <" .  $this->username . ">";
    }

    public function __construct()
    {
        parent::__construct();

        $this->roles = array('ROLE_USER');
        $this->enabled = true;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
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
     * Set program
     *
     * @param \MADSPosconsumosBundle\Entity\Program $program
     *
     * @return User
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
     * Set position
     *
     * @param string $position
     *
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @Groups({"user", "user_program", "simple_user"})
     */
    public function getEmail() 
    {
        return $this->email;
    }

    static public function secureThesePropertiesFromReports() {
        return array(
            "username",
            "usernameCanonical",
            "emailCanonical",
            "salt",
            "password",
            "confirmationToken",
            "passwordRequestedAt",
            "roles",
            "credentialsExpired",
            "credentialsExpireAt",
            "lastLogin",
            "locked",
            "expired",
            "expiresAt"
        );
    }
}
