<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Virement
 */
class Virement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $montant;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $date;
    
    /**
     * @var \Applisun\CompteBundle\Entity\Compte
     */
    private $emetteur;
    
    /**
     * @var \Applisun\CompteBundle\Entity\Compte
     */
    private $destinataire;


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
     * Set montant
     *
     * @param float $montant
     * @return Virement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Virement
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
     * Set date
     *
     * @param \DateTime $date
     * @return Virement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Set emetteur
     *
     * @param \Applisun\CompteBundle\Entity\Compte $compte
     * @return Virement
     */
    public function setEmetteur(\Applisun\CompteBundle\Entity\Compte $compte = null)
    {
        $this->emetteur = $compte;

        return $this;
    }

    /**
     * Get emetteur
     *
     * @return \Applisun\CompteBundle\Entity\Compte
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }
    
    /**
     * Set destinataire
     *
     * @param \Applisun\CompteBundle\Entity\Compte $compte
     * @return Virement
     */
    public function setDestinataire(\Applisun\CompteBundle\Entity\Compte $compte = null)
    {
        $this->destinataire = $compte;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return \Applisun\CompteBundle\Entity\Compte
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }
    
}
