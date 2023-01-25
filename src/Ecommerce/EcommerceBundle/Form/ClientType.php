<?php
/**
 * Created by PhpStorm.
 * User: l.fahimi
 * Date: 29/01/2018
 * Time: 13:50
 */

namespace Ecommerce\EcommerceBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raison')
            ->add('cfirme')
            ->add('adresse')
            ->add('telephone')
            ->add('ville')
            ->add('telephone')
            ->add('fax')
            ->add('email','email')
            ->add('nomdirigeant')
            ->add('utilisateurs', 'entity', array( 'class'    => 'UtilisateursBundle:Utilisateurs','property' => 'username', 'empty_value' => '- sélectionner un Utilisateur -', 'label'    => 'Utilisateur  ', ))
            ->add('file','file', array('required' => false))


        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ecommerce\EcommerceBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ecommerce_ecommercebundle_client';
    }
}