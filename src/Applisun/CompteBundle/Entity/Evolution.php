<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evolution
 */
class Evolution
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $solde;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \Applisun\CompteBundle\Entity\Compte
     */
    private $compte;


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
     * Set solde
     *
     * @param float $solde
     * @return Evolution
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return float 
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Evolution
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set compte
     *
     * @param \Applisun\CompteBundle\Entity\Compte $compte
     * @return Evolution
     */
    public function setCompte(\Applisun\CompteBundle\Entity\Compte $compte = null)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return \Applisun\CompteBundle\Entity\Compte 
     */
    public function getCompte()
    {
        return $this->compte;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
          $this->created_at = new \DateTime();
        }
    }
}
