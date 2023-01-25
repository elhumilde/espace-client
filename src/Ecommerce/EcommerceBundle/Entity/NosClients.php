<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NosClients
 *
 * @ORM\Table("nosclients")
 * @ORM\Entity
 */
class NosClients
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
     * @var string
     *
     * @ORM\Column(name="raison", type="string", length=100)
     */
    private $raison;

    /**
     * @var integer
     *
     * @ORM\Column(name="cfirme", type="integer")
     */
    private $cfirme;


    /**
     * One Cart has One Customer.
     * @ORM\ManyToOne(targetEntity="Telecontact", inversedBy="nosclients")
     * @ORM\JoinColumn(name="telecontact_id", referencedColumnName="id")
     */
    private $telecontact;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set raison
     *
     * @param string $raison
     * @return NosClients
     */
    public function setRaison($raison)
    {
        $this->raison = $raison;
    
        return $this;
    }

    /**
     * Get raison
     *
     * @return string 
     */
    public function getRaison()
    {
        return $this->raison;
    }

    /**
     * Set cfirme
     *
     * @param integer $cfirme
     * @return NosClients
     */
    public function setCfirme($cfirme)
    {
        $this->cfirme = $cfirme;
    
        return $this;
    }

    /**
     * Get cfirme
     *
     * @return integer 
     */
    public function getCfirme()
    {
        return $this->cfirme;
    }

    /**
     * @return mixed
     */
    public function getTelecontact()
    {
        return $this->telecontact;
    }

    /**
     * @param mixed $telecontact
     */
    public function setTelecontact($telecontact)
    {
        $this->telecontact = $telecontact;
    }



}
