<?php

namespace Tests\Emagia;

use App\Models\Game\Game;
use App\Models\Character\FighterSkills;
use App\Models\Character\FighterStats;
use App\Models\Skills\SkillInterface;
use Tests\Emagia\TestCase;

class FighterTest extends TestCase
{
    /**
     * Test to see if the two fighters are created correctly
     */
    public function testIfTheTwoFightersAreCreatedCorrectly()
    {
        $this->gameMock->bootstrap();

        $hero = Game::getFighter(HERO);

        self::assertEquals(HERO, $hero->getName());
        $this->checkStats($this->gameConfig->get('fighters>>' . HERO . '>>stats'), $hero->getStats());
        $this->checkSkills($this->gameConfig->get('fighters>>' . HERO . '>>skills'), $hero->getSkills());

        $beast = Game::getFighter(BEAST);

        self::assertEquals(BEAST, $beast->getName());
        $this->checkStats($this->gameConfig->get('fighters>>' . BEAST . '>>stats'), $beast->getStats());
        $this->checkSkills($this->gameConfig->get('fighters>>' . BEAST . '>>skills'), $beast->getSkills());
    }

    /**
     * @param array               $configStats
     * @param FighterStats $fighterStats
     */
    private function checkStats(array $configStats, FighterStats $fighterStats)
    {
        $this->checkStat($configStats['health'], $fighterStats->getHealth());
        $this->checkStat($configStats['strength'], $fighterStats->getStrength());
        $this->checkStat($configStats['defence'], $fighterStats->getDefence());
        $this->checkStat($configStats['speed'], $fighterStats->getSpeed());
        $this->checkStat($configStats['luck'], $fighterStats->getLuck());
    }

    /**
     * @param array $statsScale
     * @param int   $playerStat
     */
    private function checkStat(array $statsScale, $playerStat)
    {
        self::assertGreaterThanOrEqual($statsScale[0], $playerStat);
        self::assertLessThanOrEqual($statsScale[1], $playerStat);
    }

    /**
     * @param array                     $configSkills
     * @param FighterSkills $fighterSkills
     */
    private function checkSkills(array $configSkills, FighterSkills $fighterSkills)
    {
        self::assertCount(count($configSkills), $fighterSkills);

        /** @var SkillInterface $skill */
        foreach ($fighterSkills as $skill) {
            $skillClass = get_class($skill);
            self::assertArrayHasKey($skillClass, $configSkills);
            $configSkillChance = $configSkills[$skillClass];
            self::assertEquals($configSkillChance, $skill->getChance());
        }
    }
}
