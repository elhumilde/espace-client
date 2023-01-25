<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table("final")
 * @ORM\Entity
 */
class Finals
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
     * @ORM\Column(name="rs", type="string", length=40, nullable=true)
     */
    private $rs;


    /**
     * @var string
     *
     * @ORM\Column(name="civi", type="string", length=20, nullable=true)
     */
    private $civi;


    /**
     * @var string
     *
     * @ORM\Column(name="sign", type="string", length=50, nullable=true)
     */
    private $sign;

    /**
     * @var string
     *
     * @ORM\Column(name="cfirme", type="string", length=20, nullable=true)
     */
    private $cfirme;


    /**
     * @var array
     *
     * @ORM\Column(name="affichage", type="array", nullable=true)
     */
    private $affichage;

    /**
     * @var array
     *
     * @ORM\Column(name="referencement", type="array", nullable=true)
     */
    private $referencement;

    /**
     * @var array
     *
     * @ORM\Column(name="contenu", type="array", nullable=true)
     */
    private $contenu;
    /**
     * @var array
     *
     * @ORM\Column(name="paiement", type="array", nullable=true)
     */
    private $paiement;


    /**
     * @var string
     *
     * @ORM\Column(name="resultat", type="text", nullable=true)
     */
    private $resultat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;




    /**
     * @ORM\ManyToOne(targetEntity="Utilisateurs\UtilisateursBundle\Entity\Utilisateurs", inversedBy="emploi")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

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
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * @param string $rs
     */
    public function setRs($rs)
    {
        $this->rs = $rs;
    }

    /**
     * @return string
     */
    public function getCfirme()
    {
        return $this->cfirme;
    }

    /**
     * @param string $cfirme
     */
    public function setCfirme($cfirme)
    {
        $this->cfirme = $cfirme;
    }

    /**
     * @return string
     */
    public function getAffichage()
    {
        return $this->affichage;
    }

    /**
     * @param string $affichage
     */
    public function setAffichage($affichage)
    {
        $this->affichage = $affichage;
    }

    /**
     * @return mixed
     */
    public function getReferencement()
    {
        return $this->referencement;
    }

    /**
     * @param mixed $referencement
     */
    public function setReferencement($referencement)
    {
        $this->referencement = $referencement;
    }

    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param mixed $contenu
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    /**
     * @return array
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * @param array $paiement
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;
    }



    /**
     * @return mixed
     */
    public function getResultat()
    {
        return $this->resultat;
    }

    /**
     * @param mixed $resultat
     */
    public function setResultat($resultat)
    {
        $this->resultat = $resultat;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return string
     */
    public function getCivi()
    {
        return $this->civi;
    }

    /**
     * @param string $civi
     */
    public function setCivi($civi)
    {
        $this->civi = $civi;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param string $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }


}
