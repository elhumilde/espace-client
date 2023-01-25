<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prestation
 *
 * @ORM\Table(name="prestation")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\ReferencementRepository")
 */
class Prestation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * One Cart has One Customer.
     * @ORM\ManyToOne(targetEntity="Rubrique", inversedBy="prestation")
     * @ORM\JoinColumn(name="rubrique_id", referencedColumnName="CODERUBRIQUE")
     */
    private $rubrique;

    /**
     * @var int
     *
     * @ORM\Column(name="rubrique_id", type="integer")
     *
     */
    private $rub;


    /**
     * @return mixed
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * @param mixed $rubrique
     */
    public function setRubrique($rubrique)
    {
        $this->rubrique = $rubrique;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="PRESTATION", type="string", length=54, nullable=true)
     */
    private $prestation;

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
     * @return string
     */
    public function getPrestation()
    {
        return $this->prestation;
    }

    /**
     * @param string $prestation
     */
    public function setPrestation($prestation)
    {
        $this->prestation = $prestation;
    }

    /**
     * @return int
     */
    public function getRub()
    {
        return $this->rub;
    }

    /**
     * @param int $rub
     */
    public function setRub($rub)
    {
        $this->rub = $rub;
    }




}
