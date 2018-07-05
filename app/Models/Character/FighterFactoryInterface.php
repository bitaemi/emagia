<?php

namespace App\Models\Character;

use App\Models\Character\Fighter;

/**
 * The common contract under wich to refer for creating the Fighter objects
 * using its implementation in FighterFactory - where the config and 
 * fighter name are unspecified 
 **/

interface FighterFactoryInterface
{
    /**
     * @param string $fighterName
     * @param array  $config
     *
     * @return Fighter
     */
    public function create($fighterName, array $config = []);
}
