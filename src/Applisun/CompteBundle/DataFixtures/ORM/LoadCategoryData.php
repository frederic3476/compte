<?php

namespace Applisun\CompteBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Applisun\CompteBundle\Entity\Category;
 
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $types = array(
            array('nom'=>'Humour'),
            array('nom'=>'Romantique'),
            array('nom'=>'Politique'),
            array('nom'=>'Sexy'),
            array('nom'=>'Cinema'),
            array('nom'=>'Musique'),
        );
      
    foreach($types as $type){            
        $opeType = new Category(); 
        $opeType->setNom($type['nom']);
        $em->persist($opeType);
    }
    
    $em->flush();
  }
  
  public function getOrder()
  {
    return 2; // the order in which fixtures will be loaded
  }
}

