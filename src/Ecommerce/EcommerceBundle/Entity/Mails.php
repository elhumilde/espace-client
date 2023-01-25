<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * mails
 *
 * @ORM\Table("mails")
 * @ORM\Entity
 */
class Mails
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
     * @ORM\Column(name="cfirme", type="string", length=20, nullable=true)
     */
    private $cfirme;

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
     * @ORM\Column(name="proposition", type="string", length=20, nullable=true)
     */
    private $proposition;

    /**
     * @var string
     *
     * @ORM\Column(name="ordre", type="string", length=20, nullable=true)
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="boncommande", type="string", length=20, nullable=true)
     */
    private $bon_commande;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="joins", type="array", nullable=true)
     */
    private $join;



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
     * @var array
     *
     * @ORM\Column(name="profession", type="array", nullable=true)
     */
    private $profession;

    /**
     * @var array
     *
     * @ORM\Column(name="desrubref", type="array", nullable=true)
     */
    private $desrubref;

    /**
     * @var array
     *
     * @ORM\Column(name="code", type="array", nullable=true)
     */
    private $code;

    /**
     * @var array
     *
     * @ORM\Column(name="marque", type="array", nullable=true)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="resultat", type="text", nullable=true)
     */
    private $resultat;

   /**
     * @var integer
     *
     * @ORM\Column(name="num_bc", type="integer")
     */
    private $numbc;

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
    public function getProposition()
    {
        return $this->proposition;
    }

    /**
     * @param string $proposition
     */
    public function setProposition($proposition)
    {
        $this->proposition = $proposition;
    }

    /**
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param string $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return string
     */
    public function getBonCommande()
    {
        return $this->bon_commande;
    }

    /**
     * @param string $bon_commande
     */
    public function setBonCommande($bon_commande)
    {
        $this->bon_commande = $bon_commande;
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
     * @return array
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param array $profession
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    }

    /**
     * @return array
     */
    public function getDesrubref()
    {
        return $this->desrubref;
    }

    /**
     * @param array $desrubref
     */
    public function setDesrubref($desrubref)
    {
        $this->desrubref = $desrubref;
    }

    /**
     * @return array
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param array $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return array
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * @param array $marque
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
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

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getJoin()
    {
        return $this->join;
    }

    /**
     * @param string $join
     */
    public function setJoin($join)
    {
        $this->join = $join;
    }

    /**
     * @return int
     */
    public function getNumbc()
    {
        return $this->numbc;
    }

    /**
     * @param int $numbc
     */
    public function setNumbc($numbc)
    {
        $this->numbc = $numbc;
    }



}
