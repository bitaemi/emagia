<?php

namespace App\Models\Character;

use App\Models\Game\Game;

/** 
 * FighterRepository class is of the type FighterFactory
 * The main class FighterFactory of the common contract differs the
 * instantiantion of objects to
 * its subclasses - FighterRepository and FighterService
 * A specific subclass of Fighter for the given configuration info
 * where we make sure to create the fighter mentioned in config
 * helps to decide how the Fighter objects are created
 * 
 */
class FighterRepository implements FighterRepositoryInterface
{
    /**
     * @var FighterFactoryInterface
     */
    private $fighterFactory;

    public function __construct(FighterFactoryInterface $fighterFactory)
    {
        $this->fighterFactory = $fighterFactory;
    }

    /**
     * @param string $fighterName
     *
     * @return Fighter
     */
    public function getFighter($fighterName)
    {
        $fighterConfig = Game::getConfig()->get('fighters>>' . $fighterName);

        return $this->fighterFactory->create($fighterName, $fighterConfig);
    }
}
