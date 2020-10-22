<?php 

namespace MADSPosconsumosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EmailTemplate.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 *
 * @ORM\Table(name="email_template")
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class EmailTemplate
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
     * The email subject.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     */
    private $subject;

    /**
     * The email temaplate.
     *
     * @var string
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $template;

    /**
     * The email code.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     */
    private $code;

    /**
     * The creation date of the email template.
     *
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;


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
     * Set template
     *
     * @param string $template
     *
     * @return EmailTemplate
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return EmailTemplate
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EmailTemplate
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
     * Set subject
     *
     * @param string $subject
     *
     * @return EmailTemplate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
