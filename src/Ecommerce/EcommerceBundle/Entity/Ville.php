<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ville
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity
 */
class Ville
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeville", type="string", length=11, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codeville = '';

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=27, nullable=true)
     */
    private $libelle;


    /**
     * @ORM\ManyToOne(targetEntity="Ecommerce\EcommerceBundle\Entity\Region", cascade={"persist"})
     * @ORM\JoinColumn(name="coderegion", referencedColumnName="coderegion")
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=20, nullable=false)
     */
    private $categorie;

    /**
     * @return string
     */
    public function getCodeville()
    {
        return $this->codeville;
    }

    /**
     * @param string $codeville
     */
    public function setCodeville($codeville)
    {
        $this->codeville = $codeville;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="cat", type="integer", nullable=true)
     */
    private $cat;

    /**
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * @param string $cat
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
    }


}
