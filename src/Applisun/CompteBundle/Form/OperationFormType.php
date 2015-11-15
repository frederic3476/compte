<?php

namespace Applisun\CompteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OperationFormType extends AbstractType
{
    private $compte;
    private $securityContext;
    
    public function __construct(\Applisun\CompteBundle\Entity\Compte $compte, SecurityContext $securityContext)
    {
        $this->compte = $compte;
        $this->securityContext = $securityContext;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {      
        $builder 
            ->add('montant', 'number', array(
            	'property_path' => 'operation.montant',))
            ->add('libelle', 'text', array(
            	'property_path' => 'operation.libelle',))    
            /*->add('typeOperation','entity', array(
                    'class' => 'ApplisunCompteBundle:TypeOperation',
                    'group_by' => 'is_debit',
                    'empty_value' => 'Choisissez un type: ',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('o')
                            ->orderBy('o.is_debit', 'DESC');
                    }))*/
            ->add('debitType', 'entity', array(
                    'class' => 'ApplisunCompteBundle:TypeOperation',
                    'empty_value' => 'Choisissez un type debiteur: ',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getByTypeQueryBuilder(1);
                    }))
            ->add('creditType', 'entity', array(
                    'class' => 'ApplisunCompteBundle:TypeOperation',
                    'empty_value' => 'Choisissez un type créditeur: ',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getByTypeQueryBuilder(0);
                    })) 
            ->add('Enregistrer', 'submit');
        if (true === $options['show_compte']) {
            $builder->add('compte', 'entity', array(
                'class' => 'Applisun\CompteBundle\Entity\Compte',
            ));
        }
        
        $user = $this->securityContext->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'Ce formulaire ne peut pas être utilisé sans utilisateur connecté!'
            );
        }
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($user){
            $form = $event->getForm();
            $compte = $this->compte;  
            //TODO add 'mapped'=> false 
            $formOptions = array(
                    'required' => false,
                    'class' => 'Applisun\CompteBundle\Entity\Compte',
                    'empty_value' => 'Choisissez un compte: ',
                    'property_path' => 'operation.compte',
                    'query_builder' => function(EntityRepository $er) use ($compte) {                        
                        return $er->getCompteByUserNotCompteId(array('user' => $this->securityContext->getToken()->getUser(), 'id' => $compte->getId()));
                    },
                );
            $form->add('compte', 'entity', $formOptions);
        });
    }
    
    public function getName()
    {
        return 'applisun_operation_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'     => 'Applisun\CompteBundle\Form\Model\OperationModel',
            'show_relations' => true,

            'show_compte'      => function (Options $options) {
                // $options Symfony\Component\OptionsResolver\Options
                return $options['show_relations'];
            },
        ));
    }
}

