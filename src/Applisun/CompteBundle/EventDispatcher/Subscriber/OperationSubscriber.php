<?php

namespace Applisun\CompteBundle\EventDispatcher\Subscriber;

use Applisun\CompteBundle\EventDispatcher\Event\OperationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class OperationSubscriber
 * @package Applisun\CompteBundle\EventDispatcher\Subscriber
 */
class OperationSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            OperationEvents::PRE_ADD => array('onPreAdd', 10),            
            OperationEvents::POST_ADD => array('onPostAdd', 0),
        );
    }

    public function onPreAdd(GenericEvent $event)
    {
        $operation = $event->getSubject();


        // do something with $operation
    }

    public function onPostAdd(GenericEvent $event)
    {
        $operation = $event->getSubject();
        
        //var_dump($operation->getMontant());exit;
    }
}
