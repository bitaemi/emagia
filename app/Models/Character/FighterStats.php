<?php

namespace App\Models\Character;


class FighterStats
{
    /**
     * @var int
     */
    private $health;

    /**
     * @var int
     */
    private $strength;

    /**
     * @var int
     */
    private $defence;

    /**
     * @var int
     */
    private $speed;

    /**
     * @var int
     */
    private $luck;

    /**
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @param int $health
     *
     * @return FighterStats
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     *
     * @return FighterStats
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * @param int $defence
     *
     * @return FighterStats
     */
    public function setDefence($defence)
    {
        $this->defence = $defence;

        return $this;
    }

    /**
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     *
     * @return FighterStats
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * @return int
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * @param int $luck
     *
     * @return FighterStats
     */
    public function setLuck($luck)
    {
        $this->luck = $luck;

        return $this;
    }
}
