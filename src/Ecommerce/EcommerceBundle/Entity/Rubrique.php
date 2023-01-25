<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity
 */
class Rubrique
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
     * @ORM\OneToMany(targetEntity="Ecommerce\EcommerceBundle\Entity\Prestation", mappedBy="Rubrique")
     * @ORM\JoinColumn(nullable=true)
     */
    private $prestation;

    /**
     * @return mixed
     */
    public function getPrestation()
    {
        return $this->prestation;
    }

    /**
     * @param mixed $prestation
     */
    public function setPrestation($prestation)
    {
        $this->prestation = $prestation;
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



    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=60, nullable=true)
     */
    private $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


}


