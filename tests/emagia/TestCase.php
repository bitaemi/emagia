<?php

namespace Tests\Emagia;

use App\Models\Game\Game;
use Mockery\Mock;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use App\Models\Game\GameConfig;
use \PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class TestCase
 * @package Tests\Emagia
 */
class TestCase extends BaseTestCase
{
    /**
     * @var GameConfig
     */
    protected $gameConfig;

    /**
     * @var Logger
     */
    protected $logger;

    /** @var  Game|Mock */
    protected $gameMock;

    /**
     * Setup config and logger
     */
    public function setUp()
    {
        $this->gameConfig = $this->createGameConfig();
        $this->logger = $this->buildTestLogger();
        $this->gameMock   = \Mockery::mock(Game::class, [$this->gameConfig, $this->logger]);
    
        $this->gameMock->shouldReceive('bootstrap')
            ->withNoArgs()
            ->passthru();

        $this->gameMock->shouldReceive('getController')
            ->withNoArgs()
            ->passthru();
         // $this->gameMock->shouldReceive('metoda_de_testa')->once()->andReturn('val_ce_trebuie_sa_returneze_simularea_metodei');
         //var_dump($gameMock->metoda_de_testa()) ...
    }

    public function tearDown()
    {
        \Mockery::close();

        parent::tearDown();
    }

    /**
     * @return gameConfig
     */
    private function createGameConfig()
    {
        return (new GameConfig(['maxTurns'   => 20,
        'fighters' => [
                HERO => [
                'stats'  => [
                    'health'   => [70, 100],
                    'strength' => [70, 80],
                    'defence'  => [45, 55],
                    'speed'    => [40, 50],
                    'luck'     => [10, 30]
                ],
                'skills' => [
                    /*
                     * For 10 attacks (max) there is a => 0.10*10 chance of using Rapid strike skill
                     * For 10 defences (max) there is a => 0.20*10 chance of using Magic shield skill
                     */
                    \App\Models\Skills\RapidStrikeSkill::class    => 10,
                    \App\Models\Skills\MagicShieldSkill::class    => 20
                ]
            ],
            BEAST => [
                'stats'  => [
                    'health'   => [60, 90],
                    'strength' => [60, 90],
                    'defence'  => [40, 60],
                    'speed'    => [40, 60],
                    'luck'     => [25, 40]
                ],
                'skills' => [ 
                   
                ]
            ]
        ]
    ]));
    }

    /**
     * runs the game
     */
    protected function runGame()
    {
        $this->gameMock->bootstrap()->getController()->run();
    }

    /**
     * @return Logger
     */
    private function buildTestLogger()
    {
        return (new Logger('logger'))
            ->pushHandler(new TestHandler)
            ->pushProcessor(new PsrLogMessageProcessor);
    }
}