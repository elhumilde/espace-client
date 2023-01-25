<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * mails
 *
 * @ORM\Table("statistics")
 * @ORM\Entity
 */
class Statistics
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
     * @ORM\Column(name="code_firme", type="string", length=255, nullable=true)
     */
    private $code_firme;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;


    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=50, nullable=true)
     */
    private $sexe;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="string", length=255, nullable=true)
     */
    private $msg;

    /**
     * @var string
     *
     * @ORM\Column(name="code_commercial", type="string", length=255, nullable=true)
     */
    private $code_commercial;

    /**
     * @var string
     *
     * @ORM\Column(name="id_commercial", type="integer",nullable=true)
     */
    private $id_commercial;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer",nullable=true)
     */
    private $status;


     /**
     * @var string
     *
     * @ORM\Column(name="nom_commercial", type="string", length=255, nullable=true)
     */
    private $nom_commercial;

     /**
     * @var string
     *
     * @ORM\Column(name="time", type="datetime", nullable=true)
     */
    private $time;


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
    public function getCodeFirme()
    {
        return $this->code_firme;
    }

    /**
     * @param string $code_firme
     */
    public function setCodeFirme($code_firme)
    {
        $this->code_firme = $code_firme;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    /**
     * @return string
     */
    public function getCodeCommercial()
    {
        return $this->code_commercial;
    }

    /**
     * @param string $code_commercial
     */
    public function setCodeCommercial($code_commercial)
    {
        $this->code_commercial = $code_commercial;
    }

    /**
     * @return string
     */
    public function getIdCommercial()
    {
        return $this->id_commercial;
    }

    /**
     * @param string $id_commercial
     */
    public function setIdCommercial($id_commercial)
    {
        $this->id_commercial = $id_commercial;
    }

     /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $id_commercial
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

     /**
     * @return string
     */
    public function getNomCommercial()
    {
        return $this->nom_commercial;
    }

    /**
     * @param string $nom_commercial
     */
    public function setNomCommercial($nom_commercial)
    {
        $this->nom_commercial = $nom_commercial;
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
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

     /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }


}
