<?php

require_once __DIR__ . '/../bootstrap/constants.php';

use \App\Models\Game\Game;
use App\Models\Game\GameConfig;

$initConfig = new GameConfig(['maxTurns'   => 20,
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
    ]);
// build logger
$logFormatter = (PHP_SAPI === 'cli')
    ? new \Monolog\Formatter\LineFormatter('%message% ' . PHP_EOL)
    : new \Monolog\Formatter\LineFormatter('%message% <br/>');

$logHandler = (new \Monolog\Handler\StreamHandler('php://output'))->setFormatter($logFormatter);

$logger = (new \Monolog\Logger('logger'))
    ->pushHandler($logHandler)
    ->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());

// build and bootstrap the application
$game = new Game($initConfig, $logger);
$game->bootstrap();

return $game;
