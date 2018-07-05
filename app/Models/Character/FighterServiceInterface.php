<?php

namespace App\Models\Character;

use App\Models\Character\Fighter;


interface FighterServiceInterface
{
    /**
     * @param string $fighterName
     *
     * @return Fighter
     */
    public function getFighter($fighterName);
}
