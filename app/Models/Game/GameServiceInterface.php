<?php

namespace App\Models\Game;

use App\Models\Character\Fighter;

/**
 * Interface GameServiceInterface
 * @package App\Services
 */
interface GameServiceInterface
{
    /**
     * @return bool
     */
    public function fightersAreAlive();

    /**
     * @return Fighter
     */
    public function getFirstAttacker();

    /**
     * @param Fighter $attackingPlayer
     *
     * @return Fighter
     */
    public function getNextAttacker(Fighter $attackingPlayer);
}
