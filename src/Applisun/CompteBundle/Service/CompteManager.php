<?php

namespace Applisun\CompteBundle\Service;

use Doctrine\ORM\EntityManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Applisun\CompteBundle\EventDispatcher\Event\OperationEvents;

use Applisun\CompteBundle\Entity\Compte;
use Applisun\CompteBundle\Entity\Operation;

class CompteManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SecurityContext
     */
    private $context;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param SecurityContext $securityContext
     */
    public function __construct(EntityManager $entityManager, SecurityContext $securityContext, EventDispatcherInterface $dispatcher)
    {
        $this->em = $entityManager;
        $this->context = $securityContext;
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * Get a new operation object and given compte
     *
     * @param Compte $compte
     * @return Operation
     */
    public function getNewOperation(Compte $compte)
    {        
        $ope = new Operation();
        $ope->setCompte($compte);

        return $ope;
    }
    
    /**
     * Save an operation
     *
     * @param Operation $operation
     */
    public function saveOperation(Operation $operation, Compte $compte)
    {
        $compte->addOperation($operation);

        //listener Avertissement compte à découvert
        $this->dispatcher->dispatch(OperationEvents::POST_ADD, new GenericEvent($operation));
    }
    
}

