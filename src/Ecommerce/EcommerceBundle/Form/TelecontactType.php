<?php

namespace Ecommerce\EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TelecontactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre','textarea',array('attr' => array('class' => 'ckeditor')))
            ->add('contenu','textarea',array('attr' => array('class' => 'ckeditor')))
            ->add('experience', 'date', array ('widget' => 'choice','pattern' => '{{ day }}-{{ month }}-{{ year }', 'years'=> range(date('Y'), date('Y') - 30, -1)))
            ->add('experiececontenu','textarea',array('attr' => array('class' => 'ckeditor')));


    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ecommerce\EcommerceBundle\Entity\Telecontact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ecommerce_ecommercebundle_telecontact';
    }
}