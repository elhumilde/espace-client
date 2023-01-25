<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'Login', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(  'type' => 'password','options' => array('translation_domain' => 'FOSUserBundle'),   'first_options' => array('label' => 'form.password'),  'second_options' => array('label' => 'form.password_confirmation'), 'invalid_message' => 'fos_user.password.mismatch', ))
            ->add('nom', null, array('label' => 'Nom d\'utilisateur : ', 'translation_domain' => 'FOSUserBundle'))
            ->add('adresse', null, array('label' => 'Adresse', 'translation_domain' => 'FOSUserBundle'))
            ->add('telephone', null, array('label' => 'Téléphone', 'translation_domain' => 'FOSUserBundle','attr' => array(   'min' => 0)))
          /*  ->add('anneeExpAvtEmb', null, array('label' => 'Année d\'expérience', 'translation_domain' => 'FOSUserBundle','attr' => array(   'min' => 0)))
            ->add('nbrCltAnneePrec', null, array('label' => 'Nombre de client ', 'translation_domain' => 'FOSUserBundle','attr' => array(   'min' => 0)))*/
            ->add('anneeEmb', 'date', array ('label' => 'Date d\'embauche','widget' => 'choice','pattern' => '{{ day }}-{{ month }}-{{ year }', 'years'       => range(date('Y'), date('Y') - 30, -1)))
           /* ->add('profil', 'choice', array('choices'   => array('' => 'Select Profil','Responsable Equipe Commerciale' => 'Responsable Equipe Commerciale', 'Senior' => 'Senior','Junior' => 'Junior'),   'required'  => true,), array('label' => 'Profil', 'translation_domain' => 'FOSUserBundle'))*/
           ->add('groupe', 'choice', array('choices'   => array('' => 'Sélectionner un groupe','marketing' => 'Marketing Direct','televente' => 'Télévente', 'Kilaouy' => 'Kilaouy','Chraibi' => 'Chraibi','Benzahra' => 'Benzahra','Alami' => 'Alami')))
           ->add('profil', null, array('label' => 'Profil', 'translation_domain' => 'FOSUserBundle' ,'attr' => array('class' => 'ckeditor')))
            ->add('teleconatct',"entity", array( 'label' => false ,"class"=>"EcommerceBundle:Telecontact", "property"=>"titre",'attr'=>array('style'=>'display:none;')))
            ->add('description', null, array('label' => 'Expertise Métier', 'translation_domain' => 'FOSUserBundle','attr' => array('class' => 'ckeditor')))
            ->add('experiececontenu', null, array('label' => 'Description Experience', 'translation_domain' => 'FOSUserBundle' ,'attr' => array('class' => 'ckeditor')))
            ->add('file','file', array('label' => 'Photo de Profil','required' => false));




    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }

    public function getName()
    {
        return 'fos_user_registration';
    }
}
