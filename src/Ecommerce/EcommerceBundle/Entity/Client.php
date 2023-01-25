<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\validator\Constraints as Assert;

/**
 * Client
 *
 * @ORM\Table("client")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\ClientRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Client
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
     * @ORM\Column(name="cfirme", type="string", length=20)
     */
    private $cfirme;
    /**
     * @var string
     *
     * @ORM\Column(name="rasion", type="string", length=20)
     */
    private $raison;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50)
     */
    private $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="telephone", type="integer")
     */
    private $telephone;

    /**
     * @var integer
     *
     * @ORM\Column(name="fax", type="integer")
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nomdirigeant", type="string", length=50)
     */
    private $nomdirigeant;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=50)
     */
    private $ville;

    /**
     * @var \DateTime
     *
     * @ORM\COlumn(name="updated_at",type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateurs\UtilisateursBundle\Entity\Utilisateurs", inversedBy="client")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateurs;



    /**
     * @Assert\File(maxSize="2M", mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Merci d'envoyer un fichier au format .jpg ou .gif")
     *
     */
    public $file;



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
    public function getRaison()
    {
        return $this->raison;
    }

    /**
     * @param string $raison
     */
    public function setRaison($raison)
    {
        $this->raison = $raison;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return int
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param int $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return int
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param int $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
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
    public function getNomdirigeant()
    {
        return $this->nomdirigeant;
    }

    /**
     * @param string $nomdirigeant
     */
    public function setNomdirigeant($nomdirigeant)
    {
        $this->nomdirigeant = $nomdirigeant;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }


    public function getPath()
    {
        return $this->path;
    }
    /**
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->updateAt = new \DateTime();
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
    public function getUploadRootDir()
    {
        return __dir__.'/../../../../web/uploads';
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getAssetPath()
    {
        return 'uploads/'.$this->path;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $this->tempFile = $this->getAbsolutePath();
        $this->oldFile = $this->getPath();
        $this->updateAt = new \DateTime();

        if (null !== $this->file)
            $this->path = sha1(uniqid(mt_rand(),true)).'.'.$this->file->guessExtension();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null !== $this->file) {
            $this->file->move($this->getUploadRootDir(),$this->path);
            unset($this->file);

            if ($this->oldFile != null) unlink($this->tempFile);
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempFile = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->tempFile)) unlink($this->tempFile);
    }


    /**
     * Set path
     *
     * @param string $path
     * @return Client
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}
