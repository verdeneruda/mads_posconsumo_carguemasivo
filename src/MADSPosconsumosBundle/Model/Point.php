<?php 

namespace MADSPosconsumosBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use CrEOF\Spatial\PHP\Types\AbstractPoint;

/**
 * Class Point.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class Point extends AbstractPoint
{

	/**
     * @var float
     *
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(0)
     */
    private $latitude;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(0)
     */
    private $longitude;

    public function __construct()
    {
        if(!empty(func_get_args())) {
            $argv = $this->validateArguments(func_get_args());
            call_user_func_array(array($this, 'construct'), $argv);    
        }
    }

    /**
     * @param int      $latitude
     * @param int      $longitude
     * @param null|int $srid
     */
    protected function construct($longitude, $latitude, $srid = null)
    {
        $this->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setSrid($srid);
    }

    /**
     * @param mixed $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
    	$this->latitude = $latitude;
        return $this->setY($latitude);
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->getY();
    }

    /**
     * @param mixed $longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
    	$this->longitude = $longitude;
        return $this->setX($longitude);
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->getX();
    }

}