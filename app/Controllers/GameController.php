<?php 

namespace App\Controllers;

use App\Models\Game\Attack;
use App\Models\Game\Game;
use App\Models\Character\Fighter;
use App\Models\Game\GameServiceInterface;

/**
 * Class GameController
 * @package App\Controllers
 */
class GameController
{
    /**
     * @var GameServiceInterface
     */
    private $gameService;

    /**
     * @var int
     */
    private $remainingTurns;
    /**
     * @var Fighter
     */
    private $attackingPlayer;

    /**
     * GameController constructor.
     *
     * @param GameServiceInterface $gameService
     * @param int                  $maxTurns
     */
    public function __construct(GameServiceInterface $gameService, $maxTurns = 20)
    {
        $this->gameService = $gameService;
        $this->remainingTurns   = $maxTurns;
    }

    /**
     * Starts the game
     */
    public function run()
    {
        $this->attackingPlayer = $this->gameService->getFirstAttacker();

        while ($this->remainingTurns > 0 && $this->gameService->fightersAreAlive()) {
            Game::getLogger()->info('It\'s {name}\'s turn to attack now ... ', [
                'name' => $this->attackingPlayer->getName()
            ]);

            Game::getEmitter()->emit(
                (new Attack)->setAttacker($this->attackingPlayer)
            );

            $this->attackingPlayer = $this->gameService->getNextAttacker($this->attackingPlayer);
                // print "<pre>";
                // print_r(Game::getEmitter()->emit(
                //     (new Attack)->setAttacker($this->attackingPlayer)
                // ));
                // print "</pre>";

            $this->remainingTurns--;
        }
        Game::getLogger()->info('  ');
        Game::getLogger()->info(' Here we have ... THE END ... ');

        if ($this->gameService->fightersAreAlive()) {
            Game::getLogger()->info('Both fighters are still alive. There\'s no winner this game... ');
        } else {
            $hero = Game::getFighter(HERO);
            $beast = Game::getFighter(BEAST);
            $winner  = ($hero->isAlive()) ? $hero : $beast;
            Game::getLogger()->info('{player} wins this time!', ['player' => $winner->getName()]);
        }
    }
}
