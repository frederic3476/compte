<?php

namespace Applisun\CompteBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Applisun\CompteBundle\Entity\TypeOperation;
 
class LoadOperationTypeData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $types = array(
            array('type'=>'Alimentation', 'debit' => true),
            array('type'=>'Vente', 'debit' => false),
            array('type'=>'Remboursement', 'debit' => false),
            array('type'=>'Divers crédit', 'debit' => false),
            array('type'=>'Divers débit', 'debit' => true),
            array('type'=>'Prime', 'debit' => false),
            array('type'=>'Retraite', 'debit' => false),
            array('type'=>'Loisir', 'debit' => true),
            array('type'=>'Voiture', 'debit' => true),
            array('type'=>'Bricolage', 'debit' => true),
            array('type'=>'Cadeau', 'debit' => true),
            array('type'=>'Telephone-Internet', 'debit' => true),
            array('type'=>'Assurance', 'debit' => true),
            array('type'=>'Charge', 'debit' => true),
            array('type'=>'Travaux', 'debit' => true),
            array('type'=>'Pharmacie', 'debit' => true),
            array('type'=>'Retrait', 'debit' => true),
            array('type'=>'Impots', 'debit' => true),
            array('type'=>'Docteur-Pediatre', 'debit' => true),
            array('type'=>'Facture énergie-eau', 'debit' => true),
            array('type'=>'Facture divers', 'debit' => true),
            array('type'=>'Crédit', 'debit' => true),
            array('type'=>'Loyer', 'debit' => true),
            array('type'=>'Jardinage', 'debit' => true),
            array('type'=>'Enfant', 'debit' => true),
            array('type'=>'Virement-interne', 'debit' => true),
            array('type'=>'Virement-externe', 'debit' => false),
            array('type'=>'Allocation chômage', 'debit' => false),
            array('type'=>'Salaire', 'debit' => false),
        );
      
    foreach($types as $type){            
        $opeType = new TypeOperation(); 
        $opeType->setType($type['type']);
        $opeType->setIsDebit($type['debit']);
        $em->persist($opeType);
    }
    
    $em->flush();
  }
  
  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
}


