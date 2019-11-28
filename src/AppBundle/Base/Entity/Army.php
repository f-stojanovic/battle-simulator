<?php

namespace AppBundle\Base\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="armies")
 */

class Army extends BaseEntity
{

    const ATTACK_STRATEGY_RANDOM = 'Random';
    const ATTACK_STRATEGY_WEAKEST = 'Weakest';
    const ATTACK_STRATEGY_STRONGEST = 'Strongest';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Base\Entity\Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * @Groups({"army_data"})
     */
    private $game;

    /**
     *
     * @ORM\Column(name="name", type="string")
     * @Groups({"army_data"})
     */
    private $name;

    /**
     * @ORM\Column(name="units", type="integer")
     * @Groups({"army_data"})
     */
    private $units;

    /**
     * @ORM\Column(name="attack_strategy", type="string")
     * @Groups({"connected_device_data"})
     */
    private $attackStrategy;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param mixed $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * @return mixed
     */
    public function getAttackStrategy()
    {
        return $this->attackStrategy;
    }

    /**
     * @param mixed $attackStrategy
     */
    public function setAttackStrategy($attackStrategy)
    {
        $this->attackStrategy = $attackStrategy;
    }
}
