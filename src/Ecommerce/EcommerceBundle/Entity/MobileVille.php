<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MobileVille
 *
 * @ORM\Table(name="mobile_ville", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\ReferencementRepository")
 */
class MobileVille
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=12, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="ref", type="integer", nullable=false)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=50, nullable=false)
     */
    private $ville;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_region", type="integer", nullable=false)
     */
    private $idRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=40, nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=20, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=20, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="region_moteur", type="string", length=35, nullable=false)
     */
    private $regionMoteur;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @param int $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
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

    /**
     * @return int
     */
    public function getIdRegion()
    {
        return $this->idRegion;
    }

    /**
     * @param int $idRegion
     */
    public function setIdRegion($idRegion)
    {
        $this->idRegion = $idRegion;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getRegionMoteur()
    {
        return $this->regionMoteur;
    }

    /**
     * @param string $regionMoteur
     */
    public function setRegionMoteur($regionMoteur)
    {
        $this->regionMoteur = $regionMoteur;
    }



}
