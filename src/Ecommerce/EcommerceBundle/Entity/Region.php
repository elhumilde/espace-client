<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity
 */
class Region
{
    /**
     * @var string
     *
     * @ORM\Column(name="coderegion", type="string", length=11, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $coderegion = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=29, nullable=true)
     */
    private $libelle;

    /**
     * @return string
     */
    public function getCoderegion()
    {
        return $this->coderegion;
    }

    /**
     * @param string $coderegion
     */
    public function setCoderegion($coderegion)
    {
        $this->coderegion = $coderegion;
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



}
