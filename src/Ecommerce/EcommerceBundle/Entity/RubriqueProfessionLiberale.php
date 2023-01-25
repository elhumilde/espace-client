<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RubriqueProfessionLiberale
 *
 * @ORM\Table(name="rubrique_prof_lib")
 * @ORM\Entity
 */
class RubriqueProfessionLiberale
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CODERUBRIQUE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=65, nullable=true)
     */
    private $libelle;

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


