<?php

namespace Applisun\CompteBundle\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends \Doctrine\ORM\EntityRepository
{
    public function getMessageByCategory($catId)
    {
        $qb = $this->createQueryBuilder('m')
                ->where('m.category = :catId')
                ->orderBy('m.texte', 'ASC')                 
                ->setParameter('catId', $catId);
          
        $messages = $qb->getQuery()->getResult();
    }
}
