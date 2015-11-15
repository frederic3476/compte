<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operation
 */
class Operation
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
    private $libelle;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @var \Applisun\CompteBundle\Entity\compte
     */
    private $compte;

    /**
     * @var \Applisun\CompteBundle\Entity\typeOperation
     */
    private $typeOperation;


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
     * Set libelle
     *
     * @param string $libelle
     * @return Operation
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set montant
     *
     * @param float $montant
     * @return Operation
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Operation
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
     * @return Operation
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
     * Set compte
     *
     * @param \Applisun\CompteBundle\Entity\compte $compte
     * @return Operation
     */
    public function setCompte(\Applisun\CompteBundle\Entity\compte $compte = null)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return \Applisun\CompteBundle\Entity\compte 
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set typeOperation
     *
     * @param \Applisun\CompteBundle\Entity\typeOperation $typeOperation
     * @return Operation
     */
    public function setTypeOperation(\Applisun\CompteBundle\Entity\typeOperation $typeOperation = null)
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    /**
     * Get typeOperation
     *
     * @return \Applisun\CompteBundle\Entity\typeOperation 
     */
    public function getTypeOperation()
    {
        return $this->typeOperation;
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
    
    static public function getLuceneIndex()
    {
        if (file_exists($index = self::getLuceneIndexFile())) {
            return \Zend_Search_Lucene::open($index);
        }
 
        return \Zend_Search_Lucene::create($index);
    }
 
    static public function getLuceneIndexFile()
    {
        return __DIR__.'/../../../../web/data/operation.index';
    }

    /**
     * @ORM\PostPersist
     */
    public function updateLuceneIndex()
    {
        $index = self::getLuceneIndex();
 
        // remove existing entries
        foreach ($index->find('pk:'.$this->getId()) as $hit)
        {
          $index->delete($hit->id);
        }
 
        $doc = new \Zend_Search_Lucene_Document();
 
        // store operation primary key to identify it in the search results
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('pk', $this->getId()));
 
        // index operation fields
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('libelle', $this->getLibelle(), 'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('montant', $this->getMontant(), 'utf-8'));
 
        // add operation to the index
        $index->addDocument($doc);
        $index->commit();
    }

    /**
     * @ORM\PostRemove
     */
    public function deleteLuceneIndex()
    {
        $index = self::getLuceneIndex();
 
        foreach ($index->find('pk:'.$this->getId()) as $hit) {
            $index->delete($hit->id);
        }
    }
}
