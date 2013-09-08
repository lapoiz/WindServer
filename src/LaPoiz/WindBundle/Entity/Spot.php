<?php
namespace LaPoiz\WindBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="LaPoiz\WindBundle\Repository\SpotRepository")
 * @ORM\Table(name="windServer_Spot")
 */
class Spot 
{
    /**
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */    
    private $nom;
    
    /**
     * @ORM\Column(type="string",length=255)
     */    
    private $description;
    
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */    
    private $isKitePractice;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */    
    private $isWindsurfPractice;
    
    
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */    
    private $googleMapURL;
    
    
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */    
    private $localisationDescription;
    
    
    /**
     * @ORM\Column(type="decimal",nullable=true,scale=7)
     */    
    private $gpsLat; 
    
    /**
    * @ORM\Column(type="decimal",nullable=true,scale=7)
    */
    private $gpsLong;
    
    
    /**
     * @ORM\OneToOne(targetEntity="Balise", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(name="balise_id", referencedColumnName="id")
     */    
    private $balise;
    
    /**
     * @ORM\OneToMany(targetEntity="DataWindPrev", mappedBy="spot", cascade={"remove", "persist"} , orphanRemoval=true)
     */
    private $dataWindPrev;      
    
    
    /**
     * @ORM\OneToMany(targetEntity="WindCondition", mappedBy="spot", cascade={"remove", "persist"})
     */
    private $windCondition;
    
    
    /**
     * @ORM\OneToMany(targetEntity="MareeCondition", mappedBy="spot", cascade={"remove", "persist"})
     */
    private $mareeCondition;
    
    // balise
    
    
    
    
    
    public function __construct()
    {
        $this->dataWindPrev = new \Doctrine\Common\Collections\ArrayCollection();
        $this->windCondition = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mareeCondition = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string $nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isKitePractice
     *
     * @param boolean $isKitePractice
     */
    public function setIsKitePractice($isKitePractice)
    {
        $this->isKitePractice = $isKitePractice;
    }

    /**
     * Get isKitePractice
     *
     * @return boolean $isKitePractice
     */
    public function getIsKitePractice()
    {
        return $this->isKitePractice;
    }

    /**
     * Set isWindsurfPractice
     *
     * @param boolean $isWindsurfPractice
     */
    public function setIsWindsurfPractice($isWindsurfPractice)
    {
        $this->isWindsurfPractice = $isWindsurfPractice;
    }

    /**
     * Get isWindsurfPractice
     *
     * @return boolean $isWindsurfPractice
     */
    public function getIsWindsurfPractice()
    {
        return $this->isWindsurfPractice;
    }

    /**
     * Set googleMapURL
     *
     * @param string $googleMapURL
     */
    public function setGoogleMapURL($googleMapURL)
    {
        $this->googleMapURL = $googleMapURL;
    }

    /**
     * Get googleMapURL
     *
     * @return string $googleMapURL
     */
    public function getGoogleMapURL()
    {
        return $this->googleMapURL;
    }

    /**
     * Set localisationDescription
     *
     * @param string $localisationDescription
     */
    public function setLocalisationDescription($localisationDescription)
    {
        $this->localisationDescription = $localisationDescription;
    }

    /**
     * Get localisationDescription
     *
     * @return string $localisationDescription
     */
    public function getLocalisationDescription()
    {
        return $this->localisationDescription;
    }

    /**
     * Set balise
     *
     * @param LaPoiz\WindBundle\Entity\Balise $balise
     */
    public function setBalise(\LaPoiz\WindBundle\Entity\Balise $balise)
    {
        $this->balise = $balise;
    }

    /**
     * Get balise
     *
     * @return LaPoiz\WindBundle\Entity\Balise $balise
     */
    public function getBalise()
    {
        return $this->balise;
    }

    /**
     * Add dataWindPrev
     *
     * @param LaPoiz\WindBundle\Entity\DataWindPrev $dataWindPrev
     */
    public function addDataWindPrev(\LaPoiz\WindBundle\Entity\DataWindPrev $dataWindPrev)
    {
        $this->dataWindPrev[] = $dataWindPrev;
        $dataWindPrev->setSpot($this);
    }

    /**
     * Get dataWindPrev
     *
     * @return Doctrine\Common\Collections\Collection $dataWindPrev
     */
    public function getDataWindPrev()
    {
        return $this->dataWindPrev;
    }

    /**
     * Add windCondition
     *
     * @param LaPoiz\WindBundle\Entity\WindCondition $windCondition
     */
    public function addWindCondition(\LaPoiz\WindBundle\Entity\WindCondition $windCondition)
    {
        $this->windCondition[] = $windCondition;
    }

    /**
     * Get windCondition
     *
     * @return Doctrine\Common\Collections\Collection $windCondition
     */
    public function getWindCondition()
    {
        return $this->windCondition;
    }

    /**
     * Add mareeCondition
     *
     * @param LaPoiz\WindBundle\Entity\MareeCondition $mareeCondition
     */
    public function addMareeCondition(\LaPoiz\WindBundle\Entity\MareeCondition $mareeCondition)
    {
        $this->mareeCondition[] = $mareeCondition;
    }

    /**
     * Get mareeCondition
     *
     * @return Doctrine\Common\Collections\Collection $mareeCondition
     */
    public function getMareeCondition()
    {
        return $this->mareeCondition;
    }

    /**
     * Set gpsLat
     *
     * @param decimal $gpsLat
     */
    public function setGpsLat($gpsLat)
    {
        $this->gpsLat = $gpsLat;
    }

    /**
     * Get gpsLat
     *
     * @return decimal 
     */
    public function getGpsLat()
    {
        return $this->gpsLat;
    }

    /**
     * Set gpsLong
     *
     * @param decimal $gpsLong
     */
    public function setGpsLong($gpsLong)
    {
        $this->gpsLong = $gpsLong;
    }

    /**
     * Get gpsLong
     *
     * @return decimal 
     */
    public function getGpsLong()
    {
        return $this->gpsLong;
    }

    /**
     * Remove dataWindPrev
     *
     * @param \LaPoiz\WindBundle\Entity\DataWindPrev $dataWindPrev
     */
    public function removeDataWindPrev(\LaPoiz\WindBundle\Entity\DataWindPrev $dataWindPrev)
    {
        $this->dataWindPrev->removeElement($dataWindPrev);
    }

    /**
     * Remove windCondition
     *
     * @param \LaPoiz\WindBundle\Entity\WindCondition $windCondition
     */
    public function removeWindCondition(\LaPoiz\WindBundle\Entity\WindCondition $windCondition)
    {
        $this->windCondition->removeElement($windCondition);
    }

    /**
     * Remove mareeCondition
     *
     * @param \LaPoiz\WindBundle\Entity\MareeCondition $mareeCondition
     */
    public function removeMareeCondition(\LaPoiz\WindBundle\Entity\MareeCondition $mareeCondition)
    {
        $this->mareeCondition->removeElement($mareeCondition);
    }
}