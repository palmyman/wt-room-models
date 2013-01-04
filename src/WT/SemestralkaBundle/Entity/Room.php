<?php
namespace WT\SemestralkaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity()
 */
class Room {
	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** 
	 * popis 
	 * @ORM\Column()
	 */
	protected $name;

	/**
	 * pocet rad
	 * @Assert\Range(min="0", max="99")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 * @ORM\Column(type="integer")
	 */
	protected $rows;

	/**
	 * pocet sedadel (sloupcu)
	 * @Assert\Range(min="0", max="99")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 * @ORM\Column(type="integer")
	 */
	protected $cols;

	/**
	 * @ORM\OneToMany(targetEntity="Model", mappedBy="room")
	 */
	protected $models;

	public function __construct() {
		$this->models = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Room
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rows
     *
     * @param integer $rows
     * @return Room
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    
        return $this;
    }

    /**
     * Get rows
     *
     * @return integer 
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Set cols
     *
     * @param integer $cols
     * @return Room
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    
        return $this;
    }

    /**
     * Get cols
     *
     * @return integer 
     */
    public function getCols()
    {
        return $this->cols;
    }

    public function getCapacity() {
    return $this->rows * $this->cols;
    }

    /**
     * Add models
     *
     * @param \WT\SemestralkaBundle\Entity\Model $models
     * @return Room
     */
    public function addModel(\WT\SemestralkaBundle\Entity\Model $models)
    {
        $this->models[] = $models;
    
        return $this;
    }

    /**
     * Remove models
     *
     * @param \WT\SemestralkaBundle\Entity\Model $models
     */
    public function removeModel(\WT\SemestralkaBundle\Entity\Model $models)
    {
        $this->models->removeElement($models);
    }

    /**
     * Get models
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModels()
    {
        return $this->models;
    }
}