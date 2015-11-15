<?php

namespace Applisun\CompteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TypeOperationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('is_debit', 'checkbox', array(
                'label'     => 'Débit',
                'required'  => false,
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Applisun\CompteBundle\Entity\TypeOperation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'applisun_comptebundle_typeoperation';
    }
}
