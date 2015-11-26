<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * TypeOperation
 */
class TypeOperation
{   
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var boolean
     */
    private $is_debit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Serializer\Exclude()
     */
    private $operations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->operations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * @return TypeOperation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set is_debit
     *
     * @param boolean $isDebit
     * @return TypeOperation
     */
    public function setIsDebit($isDebit)
    {
        $this->is_debit = $isDebit;

        return $this;
    }

    /**
     * Get is_debit
     *
     * @return boolean 
     */
    public function getIsDebit()
    {
        return $this->is_debit;
    }

    /**
     * Add operations
     *
     * @param \Applisun\CompteBundle\Entity\Operation $operations
     * @return TypeOperation
     */
    public function addOperation(\Applisun\CompteBundle\Entity\Operation $operations)
    {
        $this->operations[] = $operations;

        return $this;
    }

    /**
     * Remove operations
     *
     * @param \Applisun\CompteBundle\Entity\Operation $operations
     */
    public function removeOperation(\Applisun\CompteBundle\Entity\Operation $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperations()
    {
        return $this->operations;
    }
    
    public function __toString()
    {
        return $this->type;
    }
}
