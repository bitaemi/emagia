<?php

namespace App\Models\Game;

use App\Models\Character\Fighter;
use App\Models\Character\FighterSkills;
use App\Models\Skills\AbstractOffensiveSkill;
use App\Models\Skills\SkillInterface;
use League\Event\AbstractEvent;

/**
 * Class Attack
 * @package App\Events
 */
class Attack extends AbstractEvent
{
    /**
     * @var Fighter
     */
    private $attacker;

    /**
     * @var FighterSkills
     */
    private $activeSkills;

    /**
     * @var bool
     */
    private $directAttack = true;

    /**
     * @return Fighter
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * @param Fighter $attacker
     *
     * @return Attack
     */
    public function setAttacker($attacker)
    {
        $this->attacker = $attacker;

        $this->setActiveSkills();

        return $this;
    }

    /**
     * @return bool
     */
    public function isDirectAttack()
    {
        return $this->directAttack;
    }

    /**
     * @param bool $directAttack
     *
     * @return Attack
     */
    public function setDirectAttack($directAttack)
    {
        $this->directAttack = $directAttack;

        return $this;
    }

    /**
     * @return FighterSkills
     */
    public function getActiveSkills()
    {
        return $this->activeSkills;
    }

    /**
     * @return Attack
     */
    private function setActiveSkills()
    {
        $this->activeSkills = new FighterSkills;

        if ($this->isDirectAttack()) {
            /** @var SkillInterface $skill */
            foreach ($this->attacker->getSkills() as $skill) {
                if (($skill instanceof AbstractOffensiveSkill) && $skill->shouldTrigger()) {
                    $this->activeSkills->add($skill);
                }
            }
        }

        return $this;
    }
}
