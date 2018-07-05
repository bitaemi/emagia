<?php

namespace App\Models\Character;

interface FighterRepositoryInterface
{
    /**
     * @param string $fighterName
     *
     * @return Fighter
     */
    public function getFighter($fighterName);
}
