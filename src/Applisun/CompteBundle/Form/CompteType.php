<?php

namespace Applisun\CompteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Applisun\CompteBundle\Entity\Compte;

class CompteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('nom')
            ->add('titulaire')
            ->add('solde')
            ->add('type', 'choice', array('choices' => Compte::getTypes(), 'expanded' => false, 'multiple' => false))
            ->add('banque')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Applisun\CompteBundle\Entity\Compte'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'applisun_comptebundle_compte';
    }
}
