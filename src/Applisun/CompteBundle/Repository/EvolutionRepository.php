<?php

namespace Applisun\CompteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * EvolutionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvolutionRepository extends EntityRepository
{
    public function create(\Applisun\CompteBundle\Entity\Compte $compte)
    {
        $evo = new \Applisun\CompteBundle\Entity\Evolution();
        $evo->setSolde($compte->getSolde());
        $evo->setCompte($compte);
        $this->_em->persist($evo);
        $this->_em->flush();
    }
    
    public function  getEvolutionByCompteAndYear($compteId, $year)
    {        
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('\Applisun\CompteBundle\Entity\Evolution', 'e');
        $rsm->addFieldResult('e', 'id', 'id');
        $rsm->addFieldResult('e', 'solde', 'solde');
        $rsm->addFieldResult('e', 'created_at', 'created_at');
        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT e.id, e.solde as solde, e.created_at as created_at
            FROM evolution e
            WHERE YEAR(e.created_at) = :year AND e.compte_id = :compteId
            GROUP BY MONTH(e.created_at)', $rsm
        )->setParameters(array('year' => $year, 'compteId' => $compteId));
        
        return $query->getResult();
    }    
    
    public function  getEvolutionByCompteGroupByDayAndMonthAndYear($compteId, $year)
    {        
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('\Applisun\CompteBundle\Entity\Evolution', 'e');
        $rsm->addFieldResult('e', 'id', 'id');
        $rsm->addFieldResult('e', 'solde', 'solde');
        $rsm->addFieldResult('e', 'created_at', 'created_at');
        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT e.id, e.solde as solde, e.created_at as created_at
            FROM evolution e
            WHERE YEAR(e.created_at) = :year AND e.compte_id = :compteId
            GROUP BY MONTH(e.created_at), DAY(e.created_at)', $rsm
        )->setParameters(array('year' => $year, 'compteId' => $compteId));
        
        return $query->getResult();
    }
}
