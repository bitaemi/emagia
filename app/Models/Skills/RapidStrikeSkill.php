<?php

namespace App\Models\Skills;

use App\Models\Game\Attack;
use App\Models\Game\Game;

/**
 * Class RapidStrikeSkill
 * @package App\Models\Skills
 */
class RapidStrikeSkill extends AbstractOffensiveSkill
{
    /**
     * @var string
     */
    protected $name = 'Rapid Strike Skill';

    public function handle()
    {
        Game::getLogger()->info('{attacker} triggers {skill} and gets to strike once again.', [
            'attacker' => $this->owner->getName(),
            'skill'    => $this->getName()
        ]);

        Game::getEmitter()->emit(
            (new Attack)->setDirectAttack(false)->setAttacker($this->owner)
        );
    }
}
