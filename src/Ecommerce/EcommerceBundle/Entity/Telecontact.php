<?php

namespace Ecommerce\EcommerceBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Telecontact
 *
 * @ORM\Table("telecontact")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\TelecontactRepository")
 */
class Telecontact
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="experiececontenu", type="text")
     */
    private $experiececontenu;

    /**
     * @var \Date
     *
     * @ORM\Column(name="experience", type="datetimetz")
     */
    private $experience;

   

    /**
     * @ORM\OneToMany(targetEntity="Utilisateurs\UtilisateursBundle\Entity\Utilisateurs", mappedBy="teleconatct", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateurs;
    /**
     * @ORM\OneToMany(targetEntity="Ecommerce\EcommerceBundle\Entity\NosClients", mappedBy="telecontact")
     * @ORM\JoinColumn(nullable=true)
     */
    private $nosclient;

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
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
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
     * @return mixed
     */
    public function getExperiececontenu()
    {
        return $this->experiececontenu;
    }

    /**
     * @param mixed $experiececontenu
     */
    public function setExperiececontenu($experiececontenu)
    {
        $this->experiececontenu = $experiececontenu;
    }


    /**
     * @return \Date
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param \Date $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }


    /**
     * Set utilisateurs
     *
     * @param \Utilisateurs\UtilisateursBundle\Entity\Utilisateurs $utilisateurs
     * @return Client
     */
    public function setUtilisateurs(\Utilisateurs\UtilisateursBundle\Entity\Utilisateurs $utilisateurs = null)
    {
        $this->utilisateurs = $utilisateurs;
    }

    /**
     * Get utilisateurs
     *
     * @return \Utilisateurs\UtilisateursBundle\Entity\Utilisateurs
     * @return integer
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * @return mixed
     */
    public function getNosclient()
    {
        return $this->nosclient;
    }

    /**
     * @param mixed $nosclient
     */
    public function setNosclient($nosclient)
    {
        $this->nosclient = $nosclient;
    }


}
