<?php

namespace App\Models\Game;

use App\Controllers\GameController;
use App\Models\Character\FighterFactory;

use App\Models\Character\Fighter;
use App\Models\Character\FighterRepository;
use App\Models\Character\FighterService;
use App\Models\Game\GameService;
use League\Container\Container;
use League\Event\Emitter;
use Monolog\Logger;
use App\Models\Game\GameConfig;


class Game
{
    /**
     * @var GameConfig
     */
    private $gameConfig;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Container
     */
    private static $container;

    /**
     * Game constructor.
     *
     * @param GameConfig $gameConfig
     * @param Logger $logger
     *
     * @throws Exception
     */
    public function __construct(GameConfig $gameConfig, Logger $logger)
    {
        if (!$gameConfig->has('maxTurns')) {
            throw new \Exception('No maxTurns key found in gameConfig array... ');
        }

        if (!$gameConfig->has('fighters')) {
            throw new \Exception('No fighters key found in gameConfig array... ');
        }

        if (!$gameConfig->has('fighters>>' . HERO)) {
            throw new \Exception('Hero fighter is missing from your gameConfig array ... ');
        }
        if (!$gameConfig->has('fighters>>' . BEAST)) {
            throw new \Exception('Beast fighter is missing from your gameConfig array... ');
        }

        $this->gameConfig = $gameConfig;
        $this->logger = $logger;
    }

   /**
     * 
     * Instantiates the container, subsequent use container to set 
     * configuration objects
     * @return Game
     */
    public function bootstrap()
    {
        self::$container = new Container;
        self::$container->add('gameConfig', $this->gameConfig);
        /** Add the emmiter object to the container **/
        self::$container->add('emitter', new Emitter);
        self::$container->add('logger', $this->logger);

        /** Use now the settings of the objects injected in the container **/
        $this->loadDependencies();

        /** Get the first fighter from constants and log its name and stats **/
        $hero = $this->getFighterService()->getFighter(HERO);
        $this->activeFighter($hero);

        $beast = $this->getFighterService()->getFighter(BEAST);
        $this->activeFighter($beast);

        self::$container->add($hero->getName(), $hero);
        self::$container->add($beast->getName(), $beast);

        return $this;
    }

    /**
     * @return GameConfig
     */
    public static function getConfig()
    {
        return self::$container->get('gameConfig');
    }

    /**
     * Get the fighter identified by it's configuration constantat
     *
     * @return Fighter
     */
    public static function getFighter($abiding)
    {
        return self::$container->get($abiding);
    }

    /**
     * Gets the emmiter object from container
     * @return Emitter
     */
    public static function getEmitter()
    {
        return self::$container->get('emitter');
    }

    /**
     * @return Logger
     */
    public static function getLogger()
    {
        return self::$container->get('logger');
    }

    /**
     * @return GameController
     */
    public function getController()
    {
        return self::$container->get(GameController::class);
    }

    /**
     *
     * Sets objects with the given configuration.Searches for the classes given
     * as parameter and injects them into the container thus setting up objects
     * and does nothing with thouse object until the loadDependencies call
     *
     */
    private function loadDependencies()
    {
        self::$container->add(FighterFactory::class);

        self::$container->add(GameService::class);

        self::$container->add(FighterRepository::class)
            ->withArgument(FighterFactory::class);

        self::$container->add(FighterService::class)
            ->withArgument(FighterRepository::class);
        /** Searches for GameController class and injects it into the container
         * thus creates an obj using a GameService obj and the maxTurns config's
         * key
         */
        self::$container->add(GameController::class)
            ->withArgument(GameService::class)
            ->withArgument($this->gameConfig->get('maxTurns'));
    }

    /**
     * @return FighterService
     */
    private function getFighterService()
    {
        return self::$container->get(FighterService::class);
    }

    /**
     * @param Fighter $fighter
     */
    private function activeFighter(Fighter $fighter)
    {
        self::getLogger()->info('{fighterName} stats:', ['fighterName' => $fighter->getName()]);
        self::getLogger()->info('Health: {health}', ['health' => $fighter->getStats()->getHealth()]);
        self::getLogger()->info('Strength: {strength}', ['strength' => $fighter->getStats()->getStrength()]);
        self::getLogger()->info('Defence: {defence}', ['defence' => $fighter->getStats()->getDefence()]);
        self::getLogger()->info('Speed: {speed}', ['speed' => $fighter->getStats()->getSpeed()]);
        self::getLogger()->info('Luck: {luck}%', ['luck' => $fighter->getStats()->getLuck()]);
        self::getLogger()->info('  ');
    }
}
