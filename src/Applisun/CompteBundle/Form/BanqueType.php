<?php

namespace Applisun\CompteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BanqueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url_site')
            ->add('file', 'file', array('label' => 'logo', 'required' => false));    
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Applisun\CompteBundle\Entity\Banque'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'applisun_comptebundle_banque';
    }
}
