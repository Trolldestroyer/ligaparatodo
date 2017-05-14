<?php
/**
 * (c) Ismael Trascastro <i.trascastro@gmail.com>
 *
 * @link        http://www.ismaeltrascastro.com
 * @copyright   Copyright (c) Ismael Trascastro. (http://www.ismaeltrascastro.com)
 * @license     MIT License - http://en.wikipedia.org/wiki/MIT_License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trascastro\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="Trascastro\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Liga", mappedBy="creador")
     */
    private $ligasCreadas;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ComentarioEquipo", mappedBy="creador")
     */
    private $comentariosEquiposCreados;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Player", mappedBy="creador")
     */
    private $playersCreados;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Equipo", mappedBy="creador")
     */
    private $equiposCreados;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Entrenador", mappedBy="creador")
     */
    private $entrenadoresCreados;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ronda", mappedBy="creador")
     */
    private $rondasCreadas;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Partido", mappedBy="creador")
     */
    private $partidosCreadas;
    public function __construct()
    {
        parent::__construct();

        $this->partidosCreadas = new ArrayCollection();
        $this->rondasCreadas = new ArrayCollection();
        $this->entrenadoresCreados = new ArrayCollection();
        $this->equiposCreados = new ArrayCollection();
        $this->playersCreados = new ArrayCollection();
        $this->comentariosEquiposCreados = new ArrayCollection();
        $this->ligasCreadas = new ArrayCollection();
        $this->createdAt    = new \DateTime();
        $this->updatedAt    = $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getLigasCreadas()
    {
        return $this->ligasCreadas;
    }

    /**
     * @param mixed $ligasCreadas
     */
    public function setLigasCreadas($ligasCreadas)
    {
        $this->ligasCreadas = $ligasCreadas;
    }

    public function setCreatedAt()
    {
        // never used
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate()
     *
     * @return User
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function __toString()
    {
        return $this->username;
    }
}