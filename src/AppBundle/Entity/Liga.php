<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Liga
 *
 * @ORM\Table(name="liga")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigaRepository")
 */
class Liga
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
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="ligasCreadas")
     */
    private $creador;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ronda", mappedBy="liga", cascade={"remove"})
     */
//    private $rondas;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Equipo", mappedBy="liga", cascade={"remove"})
     */
    private $equipos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->equipos = new ArrayCollection();
        $this->rondas = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
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
     * Set rondas
     *
     * @param string $rondas
     *
     * @return Liga
     */
    public function setRondas($rondas)
    {
        $this->rondas = $rondas;

        return $this;
    }

    /**
     * Get rondas
     *
     * @return string
     */
    public function getRondas()
    {
        return $this->rondas;
    }

    /**
     * Set equipos
     *
     * @param string $equipos
     *
     * @return Liga
     */
    public function setEquipos($equipos)
    {
        $this->equipos = $equipos;

        return $this;
    }

    /**
     * Get equipos
     *
     * @return string
     */
    public function getEquipos()
    {
        return $this->equipos;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Liga
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set creador
     *
     * @param string $creador
     *
     * @return Liga
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get creador
     *
     * @return string
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return Liga
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
     * Add ronda
     *
     * @param \AppBundle\Entity\Ronda $ronda
     *
     * @return Liga
     */
    public function addRondas(\AppBundle\Entity\Ronda $ronda)
    {
        $this->rondas[] = $ronda;
        return $this;
    }
    /**
     * Remove ronda
     *
     * @param \AppBundle\Entity\Ronda $rondas
     */
    public function removeRonda(\AppBundle\Entity\Ronda $rondas)
    {
        $this->rondas->removeElement($rondas);
    }

    /**
     * Add equipo
     *
     * @param \AppBundle\Entity\Equipo $equipo
     *
     * @return Liga
     */
    public function addEquipo(\AppBundle\Entity\Equipo $equipo)
    {
        $this->equipos[] = $equipo;
        return $this;
    }
    /**
     * Remove equipos
     *
     * @param \AppBundle\Entity\Equipos $equipos
     */
    public function removeEquipo(\AppBundle\Entity\Equipo $equipos)
    {
        $this->equipos->removeElement($equipos);
    }

}

