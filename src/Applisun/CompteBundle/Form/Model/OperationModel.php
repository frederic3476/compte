<?php

namespace Applisun\CompteBundle\Form\Model;

use Applisun\CompteBundle\Entity\Operation;
use Applisun\CompteBundle\Entity\TypeOperation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ExecutionContextInterface;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class OperationModel
 * @package Applisun\CompteBundle\Form\Model
 */
class OperationModel
{
    /**
     * @var Operation
     *     
     */
    private $operation;

    /**
     * @var Applisun\CompteBundle\Entity\TypeOperation
     */
    private $creditType = null;

    /**
     * @var Applisun\CompteBundle\Entity\TypeOperation
     */
    private $debitType = null;

    /**
     * Constructor
     *
     * @param Operation  $operation
     */
    public function __construct(Operation $operation)
    {
       $this->operation = $operation;
       
       if ($operation->getTypeOperation() && $operation->getTypeOperation()->getIsDebit() == 1){
           $this->debitType = $operation->getTypeOperation();
       }
       else if($operation->getTypeOperation() && $operation->getTypeOperation()->getIsDebit() == 0){
           $this->creditType = $operation->getTypeOperation();           
       }
    }

    /**
     * Get operation
     *
     * @return Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set operation
     *
     * @param Operation $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * Returns true if the operation entity is a new one.
     *
     * @return boolean
     */
    public function isNew()
    {
        return ($this->operation->getId() === null);
    }

    /**
     * get debitType
     *
     * @return Applisun\CompteBundle\Entity\TypeOperation
     */
    public function getDebitType()
    {
        return $this->debitType;
    }

    /**
     * set debitType
     *
     * @param Applisun\CompteBundle\Entity\TypeOperation
     */
    public function setDebitType(TypeOperation $type)
    {
        $this->debitType = $type;
    }

    /**
     * get creditType
     *
     * @return Applisun\CompteBundle\Entity\TypeOperation
     */
    public function getCreditType()
    {
        return $this->creditType;
    }

    /**
     * set creditType
     *
     * @param Applisun\CompteBundle\Entity\TypeOperation
     */
    public function setCreditType(TypeOperation $type)
    {
        $this->creditType = $type;
    }

    /**
     * Update the operation object according to form values.
     */
    public function getUpdatedOperation()
    {        
        if ($this->debitType){
            $this->operation->setTypeOperation($this->debitType);  
        }
        else{
            $this->operation->setTypeOperation($this->creditType);
        }

        return $this->operation;
    }
}
