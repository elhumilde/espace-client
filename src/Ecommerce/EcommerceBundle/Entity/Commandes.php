<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandes
 *
 * @ORM\Table("commandes")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\CommandesRepository")
 */
class Commandes
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
     * @ORM\ManyToOne(targetEntity="Utilisateurs\UtilisateursBundle\Entity\Utilisateurs", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true)
     */

    private $utilisateur;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="reference", type="integer")
     */
    private $reference;

    /**
     * @var array
     *
     * @ORM\Column(name="boncommande", type="array")
     */
    private $boncommande;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Commandes
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set reference
     *
     * @param integer $reference
     * @return Commandes
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return integer
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set boncommande
     *
     * @param array $boncommande
     * @return Boncommandes
     */
    public function setBoncommande($boncommande)
    {
        $this->boncommande = $boncommande;

        return $this;
    }

    /**
     * Get boncommande
     *
     * @return array
     */
    public function getBoncommande()
    {
        return $this->boncommande;
    }

    /**
     * Set utilisateur
     *
     * @param \Utilisateurs\UtilisateursBundle\Entity\Utilisateurs $utilisateur
     * @return Boncommandes
     */
    public function setUtilisateur(\Utilisateurs\UtilisateursBundle\Entity\Utilisateurs $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Utilisateurs\UtilisateursBundle\Entity\Utilisateurs
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
