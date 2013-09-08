<?php
namespace LaPoiz\WindBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="windServer_MareeCondition")
 */
class MareeCondition 
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
    private $quality;
    
    
    /**
     * @ORM\Column(type="string",length=255)
     */    
    private $maree;
    

    /**
     * @ORM\ManyToOne(targetEntity="Spot", inversedBy="mareeCondition")
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
     * Set maree
     *
     * @param string $maree
     */
    public function setMaree($maree)
    {
        $this->maree = $maree;
    }

    /**
     * Get maree
     *
     * @return string $maree
     */
    public function getMaree()
    {
        return $this->maree;
    }

    /**
     * Set spot
     *
     * @param \LaPoiz\WindBundle\Entity\Spot $spot
     * @return MareeCondition
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