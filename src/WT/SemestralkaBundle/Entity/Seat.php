<?php

namespace WT\SemestralkaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seat
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Seat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="row", type="integer")
     */
    protected $row;

    /**
     * @var integer
     *
     * @ORM\Column(name="col", type="integer")
     */
    protected $col;

    /**
     * @var boolean
     *
     * @ORM\Column(name="empty", type="boolean")
     */
    protected $empty;

    /**
     * @var boolean
     *
     * @ORM\Column(name="available", type="boolean")
     */
    protected $available;

    /**
     * @var boolean
     *
     * @ORM\Column(name="initial", type="boolean")
     */
    protected $initial;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ending", type="boolean")
     */
    protected $ending;

    /**
     * @ORM\ManyToOne(targetEntity="Model", inversedBy="seats")
     */
    protected $model;

    protected $class;

    protected $order;

    protected $price;

    public function getPrice() {
        return $this->price;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
        
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
     * Set row
     *
     * @param integer $row
     * @return Seat
     */
    public function setRow($row)
    {
        $this->row = $row;
    
        return $this;
    }

    /**
     * Get row
     *
     * @return integer 
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Set col
     *
     * @param integer $col
     * @return Seat
     */
    public function setCol($col)
    {
        $this->col = $col;
    
        return $this;
    }

    /**
     * Get col
     *
     * @return integer 
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * Set empty
     *
     * @param boolean $empty
     * @return Seat
     */
    public function setEmpty($empty)
    {
        $this->empty = $empty;
    
        return $this;
    }

    /**
     * Set available
     *
     * @param boolean $available
     * @return Seat
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    
        return $this;
    }

    /**
     * Set initial
     *
     * @param boolean $initial
     * @return Seat
     */
    public function setInitial($initial)
    {
        $this->initial = $initial;
    
        return $this;
    }

    /**
     * Get empty
     *
     * @return boolean 
     */
    public function getEmpty()
    {
        return $this->empty;
    }

    /**
     * Get available
     *
     * @return boolean 
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Get initial
     *
     * @return boolean 
     */
    public function getInitial()
    {
        return $this->initial;
    }

    /**
     * Set ending
     *
     * @param boolean $ending
     * @return Seat
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;
    
        return $this;
    }

    /**
     * Get ending
     *
     * @return boolean 
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * Set model
     *
     * @param \WT\SemestralkaBundle\Entity\Model $model
     * @return Seat
     */
    public function setModel(\WT\SemestralkaBundle\Entity\Model $model = null)
    {
        $this->model = $model;
    
        return $this;
    }

    /**
     * Get model
     *
     * @return \WT\SemestralkaBundle\Entity\Model 
     */
    public function getModel()
    {
        return $this->model;
    }   
    
}