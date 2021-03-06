<?php

namespace Applisun\CompteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Applisun\CompteBundle\Entity\Operation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * OperationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperationRepository extends EntityRepository
{        
    public function getOperationByDateAndCompte(ContainerInterface $container, $month, $year, $page, $compteId)
    {
        $maxperpage = $container->getParameter('maxperpage');
        
        $qb = $this->createQueryBuilder('o')
                ->where('YEAR(o.created_at) = :year')
                ->andWhere('o.compte = :compteId')
                ->setParameters(array('year' => $year, 'compteId' => $compteId));            
        
        if ($month !='all')
        {
            $qb->andWhere('MONTH(o.created_at) = :month');
            $qb->setParameter('month', $month);
        }
        
        $qb->orderBy('o.created_at', 'DESC');
        
        if ($page != 'all')
        {    $qb ->setFirstResult(($page-1) * $maxperpage)
                ->setMaxResults($maxperpage);
        }
        
        
        
        
        /* en DQL
         * $query = $this->createQuery(
            'SELECT o
            FROM ApplisunCompteBundle:Operation o
            WHERE MONTH(o.date) = :month AND YEAR(o.date) = :year AND o.compte_id = :compteId
            ORDER BY o.date DESC'
        )->setParameters(array('month' => $month, 'year' => $year, 'compteId' => $compteId));
         * 
         */
 
        return $qb->getQuery()->getResult();
    }
    
    public function getOperationListForplot($month, $year, $compteId)
    {
        $qb = $this->createQueryBuilder('o')
                ->select('SUM(o.montant) as somme, to.type as type')
                ->innerJoin('ApplisunCompteBundle:TypeOperation', 'to', 'WITH', 'o.typeOperation = to.id')
                ->where('YEAR(o.created_at) = :year')
                ->andWhere('to.is_debit = :debit')
                ->andWhere('YEAR(o.created_at) = :year')
                ->andWhere('o.compte = :compteId')
                ->setParameters(array('debit' => 1 ,'year' => $year, 'compteId' => $compteId));
        if ($month != '0') 
        {
            $qb->andWhere('MONTH(o.created_at) = :month');
            $qb->setParameter('month', $month);
        }
        $qb->groupBy('to.id');         
        
        return $qb->getQuery()->getResult();
    }
    
    public function getOperationFirstByCompte($compteId)
    {
        $qb = $this->createQueryBuilder('o')
                ->where('o.compte = :compteId')
                ->orderBy('o.created_at', 'ASC') 
                ->setMaxResults(1)
                ->setParameter('compteId', $compteId);
          
        $operations = $qb->getQuery()->getResult();
        return ($operations?$operations[0]:null);
    }
    
    public function getForLuceneQuery(ContainerInterface $container, $query, $page=1)
    {
        $maxperpage = $container->getParameter('maxperpage');
        
        $pks = $this->getPksForLuceneQuery($query);
         
        if (empty($pks))
        {
          return array();
        }
 
        $q = $this->createQueryBuilder('o')
            ->where('o.id IN (:pks)')
            ->setParameter('pks', $pks)
            ->orderBy('o.created_at', 'DESC')    
            ->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);
 
        return new Paginator($q);
    }
    
    public function getCountForLuceneQuery($query)
    {
        $pks = $this->getPksForLuceneQuery($query);
 
        if (empty($pks))
        {
          return array();
        }
        
        $q = $this->createQueryBuilder('o')
            ->where('o.id IN (:pks)')
            ->setParameter('pks', $pks);
 
        return count($q->getQuery()->getResult());
        
    }
    
    private function getPksForLuceneQuery($query)
    {
        $hits = Operation::getLuceneIndex()->find($query);
 
        $pks = array();
        foreach ($hits as $hit)
        {
          $pks[] = $hit->pk;
        }
        
        return $pks;
    }
}
