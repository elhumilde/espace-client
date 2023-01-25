<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table("categories")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\CategoriesRepository")
 */
class Categories
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
     * @ORM\Column(name="nomcategorie", type="string", length=125)
     */
    private $nomcategorie;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=125)
     */
    private $description;




    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomcategorie
     *
     * @param string $nomcategorie
     * @return Categories
     */
    public function setNomcategorie($nomcategorie)
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get nomcategorie
     *
     * @return string
     */
    public function getNomcategorie()
    {
        return $this->nomcategorie;
    }



    public function __toString()
    {
        return $this->getNomcategorie();
    }
}
