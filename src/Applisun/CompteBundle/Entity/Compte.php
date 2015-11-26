<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Compte
 */
class Compte
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numero;
    
    /**
     * @var string
     */
    private $nom;
    
    /**
     * @var string
     */
    private $titulaire;

    /**
     * @var integer
     */
    private $solde;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Serializer\Exclude()
     */
    private $operations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $virements_emetteur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $virements_destinataire;

    /**
     * @var \Applisun\CompteBundle\Entity\User
     */
    private $user;

    /**
     * @var \Applisun\CompteBundle\Entity\Banque
     */
    private $banque;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Serializer\Exclude()
     */
    private $evolutions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->operations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evolutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->virements_emetteur = new \Doctrine\Common\Collections\ArrayCollection();
        $this->virements_destinataire = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Compte
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * Set titulaire
     *
     * @param string $titulaire
     * @return Compte
     */
    public function setTitulaire($titulaire)
    {
        $this->titulaire = $titulaire;

        return $this;
    }

    /**
     * Get titulaire
     *
     * @return string 
     */
    public function getTitulaire()
    {
        return $this->titulaire;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Compte
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set solde
     *
     * @param integer $solde
     * @return Compte
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return integer 
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Compte
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Compte
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
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Compte
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add operations
     *
     * @param \Applisun\CompteBundle\Entity\Operation $operation
     * @return Compte
     */
    public function addOperation(\Applisun\CompteBundle\Entity\Operation $operation)
    {   
        if ( ! $this->operations->contains($operation)) {
	        $operation->setCompte($this);
	        $this->operations->add($operation);
        }
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

    /**
     * Add virements_emetteur
     *
     * @param \Applisun\CompteBundle\Entity\Virement $virementsEmetteur
     * @return Compte
     */
    public function addVirementsEmetteur(\Applisun\CompteBundle\Entity\Virement $virementsEmetteur)
    {
        $this->virements_emetteur[] = $virementsEmetteur;

        return $this;
    }

    /**
     * Remove virements_emetteur
     *
     * @param \Applisun\CompteBundle\Entity\Virement $virementsEmetteur
     */
    public function removeVirementsEmetteur(\Applisun\CompteBundle\Entity\Virement $virementsEmetteur)
    {
        $this->virements_emetteur->removeElement($virementsEmetteur);
    }

    /**
     * Get virements_emetteur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVirementsEmetteur()
    {
        return $this->virements_emetteur;
    }

    /**
     * Add virements_destinataire
     *
     * @param \Applisun\CompteBundle\Entity\Virement $virementsDestinataire
     * @return Compte
     */
    public function addVirementsDestinataire(\Applisun\CompteBundle\Entity\Virement $virementsDestinataire)
    {
        $this->virements_destinataire[] = $virementsDestinataire;

        return $this;
    }

    /**
     * Remove virements_destinataire
     *
     * @param \Applisun\CompteBundle\Entity\Virement $virementsDestinataire
     */
    public function removeVirementsDestinataire(\Applisun\CompteBundle\Entity\Virement $virementsDestinataire)
    {
        $this->virements_destinataire->removeElement($virementsDestinataire);
    }

    /**
     * Get virements_destinataire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVirementsDestinataire()
    {
        return $this->virements_destinataire;
    }

    /**
     * Set user
     *
     * @param \Applisun\CompteBundle\Entity\User $user
     * @return Compte
     */
    public function setUser(\Applisun\CompteBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Applisun\CompteBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set banque
     *
     * @param \Applisun\CompteBundle\Entity\Banque $banque
     * @return Compte
     */
    public function setBanque(\Applisun\CompteBundle\Entity\Banque $banque = null)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return \Applisun\CompteBundle\Entity\Banque 
     */
    public function getBanque()
    {
        return $this->banque;
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

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTime();
    }
    
    public static function getTypes()
    {
      return array('courant' => 'Courant', 
                    'lep' => 'LEP',
                    'ldd' => 'LDD',    
                    'pel' => 'PEL', 
                    'prep' => 'PREP', 
                    'cel' => 'CEL', 
                    'livret_A' => 'Livret A', 
                    'livret_jeune' => 'Livret Jeune', 
                    'compte_sur_livret' => 'Compte sur livret');
    }

    public static function getTypeValues()
    {
      return array_keys(self::getTypes());
    }
    
    public function __toString()
    {
        return 'Compte '.$this->getType().' / '.$this->banque->getName().' / '.$this->numero;
    }
    


    /**
     * Add evolutions
     *
     * @param \Applisun\CompteBundle\Entity\Evolution $evolutions
     * @return Compte
     */
    public function addEvolution(\Applisun\CompteBundle\Entity\Evolution $evolutions)
    {
        $this->evolutions[] = $evolutions;

        return $this;
    }

    /**
     * Remove evolutions
     *
     * @param \Applisun\CompteBundle\Entity\Evolution $evolutions
     */
    public function removeEvolution(\Applisun\CompteBundle\Entity\Evolution $evolutions)
    {
        $this->evolutions->removeElement($evolutions);
    }

    /**
     * Get evolutions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvolutions()
    {
        return $this->evolutions;
    }
}
