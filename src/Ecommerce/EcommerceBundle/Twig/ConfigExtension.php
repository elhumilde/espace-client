<?php

namespace Ecommerce\EcommerceBundle\Twig;
use Doctrine\ORM\EntityManager;


class ConfigExtension extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function getFilters()
    {
        return array(new \Twig_SimpleFilter('somme', array($this,'calculSomme')),new \Twig_SimpleFilter('wrap', array($this,'wrap')),new \Twig_SimpleFilter('sommes', array($this,'calculSommess')),new \Twig_SimpleFilter('prest', array($this,'prestations')),new \Twig_SimpleFilter('rub', array($this,'rubriques')),new \Twig_SimpleFilter('rubprof', array($this,'rubriquesprofession')));
    }

    public function calculSomme($udid)
    {
        $logoConfig = $this->em->getRepository('EcommerceBundle:Ville')->findOneBy(array('codeville'=> $udid));
        return $logoConfig ;
    }
    public function wrap($udid)
    {
        $logoConfig =wordwrap($udid, 60, "\n");
        return $logoConfig ;
    }

    public function calculSommess($udid)
    {
        $udid=intval($udid);
        $logoConfig = $this->em->getRepository('EcommerceBundle:Region')->findOneBy(array('coderegion'=> $udid));
        return $logoConfig ;
    }
    public function prestations($udid)
    {
        $udid=intval($udid);
        $logoConfig = $this->em->getRepository('EcommerceBundle:Prestation')->findOneBy(array('id'=> $udid));
        return $logoConfig ;
    }
    public function rubriques($udid)
    {
        $udid=intval($udid);
        $logoConfig = $this->em->getRepository('EcommerceBundle:Rubrique')->findOneBy(array('id'=> $udid));
        return $logoConfig ;
    }
    public function rubriquesprofession($udid)
    {
        $udid=intval($udid);
        $logoConfig = $this->em->getRepository('EcommerceBundle:RubriqueProfessionLiberale')->findOneBy(array('id'=> $udid));
        return $logoConfig ;
    }



    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'config_extension';
    }




}