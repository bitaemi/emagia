<?php

namespace App\Models\Game;

use App\Models\Game\Attack;
use App\Models\Game\Game;
use App\Models\Character\Fighter;
use App\Models\Character\FighterSkills;
use App\Models\Skills\AbstractDefensiveSkill;
use App\Models\Skills\SkillInterface;
use League\Event\AbstractListener;
use League\Event\EventInterface;


class AttackListener extends AbstractListener
{
    /**
     * @var Fighter
     */
    private $defender;

    /**
     * @var FighterSkills
     */
    private $activeSkills;

    /**
     * @return Fighter
     */
    public function getDefender()
    {
        return $this->defender;
    }

    /**
     * @param Fighter $defender
     *
     * @return AttackListener
     */
    public function setDefender($defender)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * @param EventInterface $event
     *
     * @throws Exception
     */
    public function handle(EventInterface $event)
    {
        $this->setDefenderActiveSkills();

        if (!($event instanceof Attack)) {
            throw new Exception('Invalid event.');
        }

        if ($event->getAttacker() === $this->defender) {
            return;
        }

        // clone the stats in case any activated skill modifies them
        $attackerInitialStats = clone $event->getAttacker()->getStats();

        $this->handleAttackerSkills($event);

        $grossDamage = $event->getAttacker()->getStats()->getStrength();

        if ($this->defender->isLucky()) {
            Game::getLogger()->info('{attacker} attacks but {defender} is lucky and the attacker misses.', [
                'attacker' => $event->getAttacker()->getName(),
                'defender' => $this->defender->getName()
            ]);
        } else {
            Game::getLogger()->info('{attacker} attacks {defender} and produces {damage} damage', [
                'attacker' => $event->getAttacker()->getName(),
                'defender' => $this->defender->getName(),
                'damage'   => $grossDamage

            ]);

            $this->applyDamage($grossDamage);
        }

        // reset the stats to their original value
        $event->getAttacker()->setStats($attackerInitialStats);
    }

    /**
     * @return AttackListener
     */
    private function setDefenderActiveSkills()
    {
        $this->activeSkills = new FighterSkills;

        /** @var SkillInterface $skill */
        foreach ($this->defender->getSkills() as $skill) {
            if (($skill instanceof AbstractDefensiveSkill) && $skill->shouldTrigger()) {
                $this->activeSkills->add($skill);
            }
        }

        return $this;
    }

    /**
     * @param Attack $event
     */
    private function handleAttackerSkills(Attack $event)
    {
        /** @var SkillInterface $skill */
        foreach ($event->getActiveSkills() as $skill) {
            $skill->handle();
        }
    }

    /**
     * @param int $grossDamage
     *
     * @return int
     */
    private function handleDefenderSkills($grossDamage)
    {
        /** @var SkillInterface $skill */
        foreach ($this->activeSkills as $skill) {
            $grossDamage = $skill->handle($grossDamage);
        }

        return $grossDamage;
    }

    /**
     * @param int $grossDamage
     */
    private function applyDamage($grossDamage)
    {
        if (!$this->defender->isAlive()) {
            return;
        }

        // clone the stats in case any activated skill modifies them
        $defenderInitialStats = clone $this->defender->getStats();

        // the damage taken could be mitigated by skills
        $grossDamage = $this->handleDefenderSkills($grossDamage);

        $health          = $this->defender->getStats()->getHealth();
        $defence         = $this->defender->getStats()->getDefence();
        $damage          = ($grossDamage >= $defence) ? ($grossDamage - $defence) : 0;
        $absorbedDamage  = $grossDamage - $damage;
        $remainingHealth = $health - $damage;
        $damageTaken     = ($health > $damage) ? $damage : $health;
        $overkillDamage  = ($health > $damage) ? 0 : ($damage - $health);


        if ($health > $damage) {
            $this->defender->getStats()->setHealth($remainingHealth);
            Game::getLogger()->info(
                '{defender} was striked by {damage} ({absorbedDamage} absorbed) and his health is now down to ' .
                '{health}',
                [
                    'defender'       => $this->defender->getName(),
                    'damage'         => $damageTaken,
                    'absorbedDamage' => $absorbedDamage,
                    'health'         => $remainingHealth
                ]
            );
        } else {
            Game::getLogger()->info(
                '{defender} receives a hit of {damage} ({absorbedDamage} absorbed, {overkill} overkill) and ' .
                'falls to the ground.',
                [
                    'defender'       => $this->defender->getName(),
                    'damage'         => $damageTaken,
                    'absorbedDamage' => $absorbedDamage,
                    'overkill'       => $overkillDamage
                ]
            );
            $this->defender->getStats()->setHealth(0);
        }

        // reset the stats to their original value, keep the health value
        $defenderInitialStats->setHealth($this->defender->getStats()->getHealth());
        $this->defender->setStats($defenderInitialStats);
    }
}
