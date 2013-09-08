<?php
namespace LaPoiz\WindBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="windServer_WindCondition")
 */
class WindCondition 
{
    /**
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    
    
    /**
     * @ORM\Column(type="string",length=255)
     */    
    private $orientation;
    
    /**
     * @ORM\Column(type="string",length=255)
     */    
    private $quality;
    

    /**
     * @ORM\ManyToOne(targetEntity="Spot", inversedBy="windCondition")
     * @ORM\JoinColumn(name="spot_id", referencedColumnName="id")
     */
    private $spot;   
    


    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orientation
     *
     * @param string $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * Get orientation
     *
     * @return string $orientation
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set quality
     *
     * @param string $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * Get quality
     *
     * @return string $quality
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set spot
     *
     * @param \LaPoiz\WindBundle\Entity\Spot $spot
     * @return WindCondition
     */
    public function setSpot(\LaPoiz\WindBundle\Entity\Spot $spot = null)
    {
        $this->spot = $spot;
    
        return $this;
    }

    /**
     * Get spot
     *
     * @return \LaPoiz\WindBundle\Entity\Spot 
     */
    public function getSpot()
    {
        return $this->spot;
    }
}