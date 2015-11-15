<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pret
 */
class Pret
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
     * @var float
     */
    private $taux;

    /**
     * @var float
     */
    private $mensualite;

    /**
     * @var \DateTime
     */
    private $date_fin;


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
     * @return Pret
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
     * Set taux
     *
     * @param float $taux
     * @return Pret
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return float 
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set mensualite
     *
     * @param float $mensualite
     * @return Pret
     */
    public function setMensualite($mensualite)
    {
        $this->mensualite = $mensualite;

        return $this;
    }

    /**
     * Get mensualite
     *
     * @return float 
     */
    public function getMensualite()
    {
        return $this->mensualite;
    }

    /**
     * Set date_fin
     *
     * @param \DateTime $dateFin
     * @return Pret
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;

        return $this;
    }

    /**
     * Get date_fin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }
}
