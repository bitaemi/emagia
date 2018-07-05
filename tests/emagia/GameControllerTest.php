<?php

namespace Tests\Emagia;

use Monolog\Handler\TestHandler;

/**
 * Class GameControllerTest
 * @package Tests\App\Controllers
 */
class GameControllerTest extends TestCase
{
    /**
     * run game with mocked gameConfig without skills where Orderus starts first and wins
     */
    public function testGameWithMockedConfigWhereOrderusStartsFirstAndWinsWithNoSkills()
    {
        $this->gameConfig->set('fighters>>' . HERO . '>>skills', []);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>strength', 100);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>speed', 100);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>luck', 100);

        $this->gameConfig->set('fighters>>' . BEAST . '>>skills', []);
        $this->gameConfig->set('fighters>>' . BEAST . '>>stats>>speed', 50);
        $this->gameConfig->set('fighters>>' . BEAST . '>>stats>>luck', 0);
        $this->gameConfig->set('fighters>>' . BEAST . '>>stats>>defence', 70);
        
        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();

        self::assertTrue($logHandler->hasInfoThatContains(HERO . ' strikes first because is the fastest.'));
        self::assertTrue($logHandler->hasInfoThatContains(HERO . ' wins this time!'));
    }

    /**
     * Test the game with mocked configuration where Wild Beast starts first and wins
     * Ordeus has no skills
     */
    public function testGameWithMockedConfigWhereWildBeastStartsFirstAndWins()
    {
        $this->gameConfig->set('fighters>>' . BEAST . '>>skills', []);
        $this->gameConfig->set('fighters>>' . BEAST . '>>stats>>strength', 100);
        $this->gameConfig->set('fighters>>' . BEAST . '>>stats>>speed', 100);
        $this->gameConfig->set('fighters>>' . BEAST . '>>stats>>luck', 100);

        $this->gameConfig->set('fighters>>' . HERO . '>>skills', []);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>speed', 50);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>luck', 0);
        $this->gameConfig->set('fighters>>' . HERO . '>>stats>>defence', 0);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue($logHandler->hasInfoThatContains(BEAST . ' strikes first because is the fastest.'));
        self::assertTrue($logHandler->hasInfoThatContains(BEAST . ' wins this time!'));
    }
}
