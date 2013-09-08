<?php
namespace LaPoiz\WindBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="windServer_Maree")
 */
class Maree 
{
    /**
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="time")
     */
    private $time;
    
    
    /**
     * @ORM\Column(type="integer")
     */
    private $coef;
    
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isMareeHaute;
    
        

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
     * Set time
     *
     * @param time $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return time $time
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set coef
     *
     * @param integer $coef
     */
    public function setCoef($coef)
    {
        $this->coef = $coef;
    }

    /**
     * Get coef
     *
     * @return integer $coef
     */
    public function getCoef()
    {
        return $this->coef;
    }

    /**
     * Set mareeHaute
     *
     * @param boolean $mareeHaute
     */
    public function setMareeHaute($mareeHaute)
    {
        $this->mareeHaute = $mareeHaute;
    }

    /**
     * Get mareeHaute
     *
     * @return boolean $mareeHaute
     */
    public function getMareeHaute()
    {
        return $this->mareeHaute;
    }

    /**
     * Set isMareeHaute
     *
     * @param boolean $isMareeHaute
     */
    public function setIsMareeHaute($isMareeHaute)
    {
        $this->isMareeHaute = $isMareeHaute;
    }

    /**
     * Get isMareeHaute
     *
     * @return boolean $isMareeHaute
     */
    public function getIsMareeHaute()
    {
        return $this->isMareeHaute;
    }
}