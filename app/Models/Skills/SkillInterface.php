<?php

namespace App\Models\Skills;

/**
 * Interface SkillInterface
 * @package App\Models\Skills
 */
interface SkillInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return AbstractSkill
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getChance();

    /**
     * @param int $chance
     *
     * @return AbstractSkill
     */
    public function setChance($chance);

    /**
     * @return bool
     */
    public function shouldTrigger();
}
