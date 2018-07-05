<?php

namespace Tests\Emagia;

use App\Models\Skills\RapidStrikeSkill;
use Monolog\Handler\TestHandler;

class RapidStrikeSkillTest extends TestCase
{
    /**
     * Test game with mocked gameConfig where Orderus triggers the Rapid Strike
     */
    public function testUsingMockConfigRapidStrikeWhenOrderusTriggersTheSkill()
    {
        $this->gameConfig->set('fighters>>' . HERO . '>>skills', [
            RapidStrikeSkill::class => 100
        ]);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>luck', 100);

        $this->gameConfig->set('fighters>>' . BEAST . '>>skills', []);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue(
            $logHandler->hasInfoThatContains(HERO . ' triggers Rapid Strike Skill and gets to strike once again.')
        );
    }
}
