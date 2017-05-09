<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Equipo
 *
 * @ORM\Table(name="equipo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EquipoRepository")
 */
class Equipo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255)
     * @Assert\NotBlank(message="Name cannot be empty")
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     minMessage="Description too short!",
     *     maxMessage="Description too long!"
     * )
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="trofeos", type="string", length=255)
     */
    private $trofeos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Player", mappedBy="equipo", cascade={"remove"})
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ComentarioEquipo", mappedBy="equipo", cascade={"remove"})
     */
    private $comentarios;

    /**
     * @var string
     *
     * @ORM\Column(name="localizacion", type="string", length=255)
     */
    private $localizacion;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos", type="integer", length=255)
     */
    private $puntos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Entrenador", mappedBy="equipo", cascade={"remove"})
     */
    private $entrenadores;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="equiposCreados")
     */
    private $creador;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Liga", inversedBy="liga")
     */
    private $liga;

    public function __construct()
    {
        $this->puntos = 0;
        $this->trofeos = "";
        $this->entrenadores = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * @param mixed $creador
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;
    }

    /**
     * @return mixed
     */
    public function getLiga()
    {
        return $this->liga;
    }

    /**
     * @param mixed $liga
     */
    public function setLiga($liga)
    {
        $this->liga = $liga;
    }

    /**
     * @return mixed
     */
    public function getEntrenadores()
    {
        return $this->entrenadores;
    }

    /**
     * @param mixed $entrenadores
     */
    public function setEntrenador($entrenadores)
    {
        $this->entrenadores = $entrenadores;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getLocalizacion()
    {
        return $this->localizacion;
    }

    /**
     * @param string $localizacion
     */
    public function setLocalizacion($localizacion)
    {
        $this->localizacion = $localizacion;
    }

    /**
     * @return int
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * @param int $puntos
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;
    }


    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return Equipo
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set trofeos
     *
     * @param string $trofeos
     *
     * @return Equipo
     */
    public function setTrofeos($trofeos)
    {
        $this->trofeos = $trofeos;

        return $this;
    }

    /**
     * Get trofeos
     *
     * @return string
     */
    public function getTrofeos()
    {
        return $this->trofeos;
    }

    /**
     * Set players
     *
     * @param string $players
     *
     * @return Equipo
     */
    public function setPlayers($players)
    {
        $this->players = $players;

        return $this;
    }

    /**
     * Get players
     *
     * @return string
     */
    public function getPlayers()
    {
        return $this->players;
    }


    /**
     * Set wins
     *
     * @param integer $wins
     *
     * @return Equipo
     */
    public function setWins($wins)
    {
        $this->wins = $wins;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }


}

