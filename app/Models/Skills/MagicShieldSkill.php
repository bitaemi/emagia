<?php

namespace App\Models\Skills;

use App\Models\Game\Game;

/**
 * Class MagicShieldSkill
 * @package App\Models\Skills
 */
class MagicShieldSkill extends AbstractDefensiveSkill
{
    /**
     * @var string
     */
    protected $name = 'Magic Shield Skill';

    /**
     * @param int $grossDamage
     *
     * @return int
     */
    public function handle($grossDamage)
    {
        Game::getLogger()->info('This is the one in 20 chances for {defender} to use his {skill} and take only half of the damage produced by the attacker.', [
            'defender' => $this->owner->getName(),
            'skill'    => $this->getName()
        ]);

        return ($grossDamage / 2);
    }
}
