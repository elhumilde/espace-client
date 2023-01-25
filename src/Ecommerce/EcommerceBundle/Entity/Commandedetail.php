<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandedetail
 *
 * @ORM\Table("Commandedetail")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\CommandedetailRepository")
 */
class Commandedetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Ecommerce\EcommerceBundle\Entity\Commandes", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Commandes;


    /**
     * @ORM\ManyToOne(targetEntity="Ecommerce\EcommerceBundle\Entity\Produits", inversedBy="produits")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Produits;

    /**
     * @var integer
     *
     * @ORM\Column(name="Prix_htc", type="integer")
     */
    private $Prix_htc;

    /**
     * @return int
     */
    public function getPrixHtc()
    {
        return $this->Prix_htc;
    }

    /**
     * @param int $Prix_htc
     */
    public function setPrixHtc($Prix_htc)
    {
        $this->Prix_htc = $Prix_htc;
    }

    /**
     * @return mixed
     */
    public function getProduits()
    {
        return $this->Produits;
    }

    /**
     * @param mixed $Produits
     */
    public function setProduits($Produits)
    {
        $this->Produits = $Produits;
    }

    /**
     * @return mixed
     */
    public function getCommandes()
    {
        return $this->Commandes;
    }

    /**
     * @param mixed $Commandes
     */
    public function setCommandes($Commandes)
    {
        $this->Commandes = $Commandes;
    }


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


}
