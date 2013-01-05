<?php
namespace WT\SemestralkaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Testovaci trida
 * @ORM\Entity()
 */
class Model {

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @Assert\NotBlank 
     * @ORM\Column()
     */
    protected $author;

    /**
     * @Assert\NotBlank 
     * @ORM\Column()
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    protected $capacity;

	/**
	 * @ORM\ManyToOne(targetEntity="Room", inversedBy="models")
	 */
	protected $room;

    /**
     * @ORM\OneToMany(targetEntity="Seat", mappedBy="model")
     */
    protected $seats;

    /**
     * @Assert\File(maxSize="1000000")
     */
    public $file;

    public function getSizeOfGroup() {
        return $this->sizeOfGroup;
    }

    public function setSizeOfGroup($sizeOfGroup) {
        $this->sizeOfGroup = $sizeOfGroup;
        return $this;
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
     * Get capacity
     *
     * @return integer 
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * capacity++
     *
     * @return Model
     */
    public function incCapacity()
    {
        $this->capacity = $this->capacity + 1;
    
        return $this;
    }

    /**
     * capacity--
     *
     * @return Model
     */
    public function decCapacity()
    {
        $this->capacity = $this->capacity - 1;
    
        return $this;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Model
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set room
     *
     * @param \WT\SemestralkaBundle\Entity\Room $room
     * @return Model
     */
    public function setRoom(\WT\SemestralkaBundle\Entity\Room $room = null)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return \WT\SemestralkaBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seats = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add seats
     *
     * @param \WT\SemestralkaBundle\Entity\Seat $seats
     * @return Model
     */
    public function addSeat(\WT\SemestralkaBundle\Entity\Seat $seats)
    {
        $this->seats[] = $seats;
    
        return $this;
    }

    /**
     * Remove seats
     *
     * @param \WT\SemestralkaBundle\Entity\Seat $seats
     */
    public function removeSeat(\WT\SemestralkaBundle\Entity\Seat $seats)
    {
        $this->seats->removeElement($seats);
    }

    /**
     * Get seats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Model
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     * @return Model
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    
        return $this;
    }
}