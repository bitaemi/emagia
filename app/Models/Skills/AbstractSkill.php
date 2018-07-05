<?php

namespace App\Models\Skills;

use App\Models\Character\Fighter;

/**
 * Class AbstractSkill
 * @package App\Models\Skills
 */
abstract class AbstractSkill implements SkillInterface
{
    /**
     * @var Fighter
     */
    protected $owner;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    private $chance;

    /**
     * AbstractSkill constructor.
     *
     * @param Fighter $owner
     */
    public function __construct(Fighter $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return AbstractSkill
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * @param int $chance
     *
     * @return AbstractSkill
     */
    public function setChance($chance)
    {
        $this->chance = $chance;

        return $this;
    }

    /**
     * @return bool
     */
    public function shouldTrigger()
    {
        return (rand(0, 100) < $this->chance);
    }
}
