<?php

namespace Ecommerce\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * TarifInternet
 *
 * @ORM\Table("tarif_internet")
 * @ORM\Entity(repositoryClass="Ecommerce\EcommerceBundle\Repository\TarifInternetRepository")
 */
class TarifInternet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="CTAR", type="string", length=2, nullable=true)
     */
    private $ctar;

    /**
     * @var string
     *
     * @ORM\Column(name="Famille", type="string", length=13, nullable=true)
     */
    private $famille;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=28, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="OPT1", type="string", length=1, nullable=true)
     */
    private $opt1;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_OPT1", type="string", length=25, nullable=true)
     */
    private $libOpt1;

    /**
     * @var integer
     *
     * @ORM\Column(name="OPT2", type="integer", nullable=true)
     */
    private $opt2;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_OPT2", type="string", length=12, nullable=true)
     */
    private $libOpt2;

    /**
     * @var integer
     *
     * @ORM\Column(name="OPT3", type="integer", nullable=true)
     */
    private $opt3;

    /**
     * @var integer
     *
     * @ORM\Column(name="LIB_OPT3", type="integer", nullable=true)
     */
    private $libOpt3;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_1", type="integer", nullable=true)
     */
    private $rGion1;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_2", type="integer", nullable=true)
     */
    private $rGion2;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_3", type="integer", nullable=true)
     */
    private $rGion3;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_4", type="integer", nullable=true)
     */
    private $rGion4;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_5", type="integer", nullable=true)
     */
    private $rGion5;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_6", type="integer", nullable=true)
     */
    private $rGion6;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_7", type="integer", nullable=true)
     */
    private $rGion7;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_8", type="integer", nullable=true)
     */
    private $rGion8;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_9", type="integer", nullable=true)
     */
    private $rGion9;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_10", type="integer", nullable=true)
     */
    private $rGion10;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_11", type="integer", nullable=true)
     */
    private $rGion11;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_12", type="integer", nullable=true)
     */
    private $rGion12;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_13", type="integer", nullable=true)
     */
    private $rGion13;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_14", type="integer", nullable=true)
     */
    private $rGion14;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_15", type="integer", nullable=true)
     */
    private $rGion15;

    /**
     * @var integer
     *
     * @ORM\Column(name="R_gion_16", type="integer", nullable=true)
     */
    private $rGion16;

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
    public function getCtar()
    {
        return $this->ctar;
    }

    /**
     * @param string $ctar
     */
    public function setCtar($ctar)
    {
        $this->ctar = $ctar;
    }

    /**
     * @return string
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * @param string $famille
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getOpt1()
    {
        return $this->opt1;
    }

    /**
     * @param string $opt1
     */
    public function setOpt1($opt1)
    {
        $this->opt1 = $opt1;
    }

    /**
     * @return string
     */
    public function getLibOpt1()
    {
        return $this->libOpt1;
    }

    /**
     * @param string $libOpt1
     */
    public function setLibOpt1($libOpt1)
    {
        $this->libOpt1 = $libOpt1;
    }

    /**
     * @return int
     */
    public function getOpt2()
    {
        return $this->opt2;
    }

    /**
     * @param int $opt2
     */
    public function setOpt2($opt2)
    {
        $this->opt2 = $opt2;
    }

    /**
     * @return string
     */
    public function getLibOpt2()
    {
        return $this->libOpt2;
    }

    /**
     * @param string $libOpt2
     */
    public function setLibOpt2($libOpt2)
    {
        $this->libOpt2 = $libOpt2;
    }

    /**
     * @return int
     */
    public function getOpt3()
    {
        return $this->opt3;
    }

    /**
     * @param int $opt3
     */
    public function setOpt3($opt3)
    {
        $this->opt3 = $opt3;
    }

    /**
     * @return int
     */
    public function getLibOpt3()
    {
        return $this->libOpt3;
    }

    /**
     * @param int $libOpt3
     */
    public function setLibOpt3($libOpt3)
    {
        $this->libOpt3 = $libOpt3;
    }

    /**
     * @return int
     */
    public function getRGion1()
    {
        return $this->rGion1;
    }

    /**
     * @param int $rGion1
     */
    public function setRGion1($rGion1)
    {
        $this->rGion1 = $rGion1;
    }

    /**
     * @return int
     */
    public function getRGion2()
    {
        return $this->rGion2;
    }

    /**
     * @param int $rGion2
     */
    public function setRGion2($rGion2)
    {
        $this->rGion2 = $rGion2;
    }

    /**
     * @return int
     */
    public function getRGion3()
    {
        return $this->rGion3;
    }

    /**
     * @param int $rGion3
     */
    public function setRGion3($rGion3)
    {
        $this->rGion3 = $rGion3;
    }

    /**
     * @return int
     */
    public function getRGion4()
    {
        return $this->rGion4;
    }

    /**
     * @param int $rGion4
     */
    public function setRGion4($rGion4)
    {
        $this->rGion4 = $rGion4;
    }

    /**
     * @return int
     */
    public function getRGion5()
    {
        return $this->rGion5;
    }

    /**
     * @param int $rGion5
     */
    public function setRGion5($rGion5)
    {
        $this->rGion5 = $rGion5;
    }

    /**
     * @return int
     */
    public function getRGion6()
    {
        return $this->rGion6;
    }

    /**
     * @param int $rGion6
     */
    public function setRGion6($rGion6)
    {
        $this->rGion6 = $rGion6;
    }

    /**
     * @return int
     */
    public function getRGion7()
    {
        return $this->rGion7;
    }

    /**
     * @param int $rGion7
     */
    public function setRGion7($rGion7)
    {
        $this->rGion7 = $rGion7;
    }

    /**
     * @return int
     */
    public function getRGion8()
    {
        return $this->rGion8;
    }

    /**
     * @param int $rGion8
     */
    public function setRGion8($rGion8)
    {
        $this->rGion8 = $rGion8;
    }

    /**
     * @return int
     */
    public function getRGion9()
    {
        return $this->rGion9;
    }

    /**
     * @param int $rGion9
     */
    public function setRGion9($rGion9)
    {
        $this->rGion9 = $rGion9;
    }

    /**
     * @return int
     */
    public function getRGion10()
    {
        return $this->rGion10;
    }

    /**
     * @param int $rGion10
     */
    public function setRGion10($rGion10)
    {
        $this->rGion10 = $rGion10;
    }

    /**
     * @return int
     */
    public function getRGion11()
    {
        return $this->rGion11;
    }

    /**
     * @param int $rGion11
     */
    public function setRGion11($rGion11)
    {
        $this->rGion11 = $rGion11;
    }

    /**
     * @return int
     */
    public function getRGion12()
    {
        return $this->rGion12;
    }

    /**
     * @param int $rGion12
     */
    public function setRGion12($rGion12)
    {
        $this->rGion12 = $rGion12;
    }

    /**
     * @return int
     */
    public function getRGion13()
    {
        return $this->rGion13;
    }

    /**
     * @param int $rGion13
     */
    public function setRGion13($rGion13)
    {
        $this->rGion13 = $rGion13;
    }

    /**
     * @return int
     */
    public function getRGion14()
    {
        return $this->rGion14;
    }

    /**
     * @param int $rGion14
     */
    public function setRGion14($rGion14)
    {
        $this->rGion14 = $rGion14;
    }

    /**
     * @return int
     */
    public function getRGion15()
    {
        return $this->rGion15;
    }

    /**
     * @param int $rGion15
     */
    public function setRGion15($rGion15)
    {
        $this->rGion15 = $rGion15;
    }

    /**
     * @return int
     */
    public function getRGion16()
    {
        return $this->rGion16;
    }

    /**
     * @param int $rGion16
     */
    public function setRGion16($rGion16)
    {
        $this->rGion16 = $rGion16;
    }




}
