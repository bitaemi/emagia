<?php

namespace App\Models\Game;

use App\Models\Game\Game;
use App\Models\Character\Fighter;


class GameService implements GameServiceInterface
{
    /**
     * @return bool
     */
    public function fightersAreAlive()
    {
        return (Game::getFighter(HERO)->isAlive() && Game::getFighter(BEAST)->isAlive());
    }

    /**
     * @return Fighter
     */
    public function getFirstAttacker()
    {
        $hero  = Game::getFighter(HERO);
        $beast  = Game::getFighter(BEAST);
        $attacker = null;

        if ($hero->getStats()->getSpeed() > $beast->getStats()->getSpeed()) {
            $attacker = $hero;
        } elseif ($hero->getStats()->getSpeed() < $beast->getStats()->getSpeed()) {
            $attacker = $beast;
        }

        if ($attacker !== null) {
            Game::getLogger()->info('{name} strikes first because is the fastest.', [
                'name' => $attacker->getName()
            ]);
        } else {
            $attacker = ($hero->getStats()->getLuck() > $beast->getStats()->getLuck())
                ? $hero
                : $beast;
            Game::getLogger()->info('{name} is equaly fast but luckier thus strikes first.', [
                'name' => $attacker->getName()
            ]);
        }

        return $attacker;
    }

    /**
     * @param Fighter $attackingPlayer
     *
     * @return Fighter
     */
    public function getNextAttacker(Fighter $attackingPlayer)
    {
        $hero = Game::getFighter(HERO);
        $beast = Game::getFighter(BEAST);

        return ($attackingPlayer->getName() === $hero->getName())
            ? $beast
            : $hero;
    }
}
