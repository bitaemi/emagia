<?php

namespace Tests\Emagia;

use App\Models\Skills\MagicShieldSkill;
use Monolog\Handler\TestHandler;

class MagicShieldSkillTest extends TestCase
{
    /**
     * run game with mocked gameConfig where Orderus triggers the skill
     */
    public function testRunGameWithMockedConfigWhereOrderusTriggersTheSkill()
    {
        $this->gameConfig->set('fighters>>' . HERO . '>>skills', [
            MagicShieldSkill::class => 100
        ]);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>luck', 0);

        $this->gameConfig->set('fighters>>' . BEAST . '>>skills', []);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue(
            $logHandler->hasInfoThatContains(
                'This is the one in 20 chances for ' . HERO . ' to use his Magic Shield Skill and take only half of the damage produced by the attacker.'
            )
        );
    }
}
