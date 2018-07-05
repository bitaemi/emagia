<?php

namespace App\Models\Character;

use App\Models\Game\Attack;
use App\Models\Game\Game;
use App\Models\Game\AttackListener;

class Fighter
{
    /**
     * @var
     */
    private $name;


    /**
     * @var FighterStats
     */
    private $stats;

    /**
     * @var FighterSkills
     */
    private $skills = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Fighter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return FighterStats
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param FighterStats $stats
     *
     * @return Fighter
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @return FighterSkills
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param FighterSkills $skills
     *
     * @return Fighter
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        return ($this->getStats()->getHealth() > 0);
    }

    /**
     * @return bool
     */
    public function isLucky()
    {
        return (rand(0, 100) < $this->getStats()->getLuck());
    }

    public function addListeners()
    {
        Game::getEmitter()->addListener(
            Attack::class,
            (new AttackListener)->setDefender($this)
        );
    }
}
