<?php
namespace Applisun\CompteBundle\EventDispatcher\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Applisun\CompteBundle\Entity\Compte;
use Applisun\CompteBundle\Entity\Operation;

class CompteListener {
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        if ($entity instanceof Compte) {
            $entityManager->getRepository('Applisun\CompteBundle\Entity\Evolution')->create($entity);
        }
        else if ($entity instanceof Operation) {
            $entityManager->getRepository('Applisun\CompteBundle\Entity\Compte')->updateSolde($entity, 'add');
        }
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        if ($entity instanceof Compte) {
            $entityManager->getRepository('Applisun\CompteBundle\Entity\Evolution')->create($entity);
        }
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        if ($entity instanceof Operation) {
            $entityManager->getRepository('Applisun\CompteBundle\Entity\Compte')->updateSolde($entity, 'remove');
        }
    }
}
