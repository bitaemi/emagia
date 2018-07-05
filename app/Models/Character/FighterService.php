<?php

namespace App\Models\Character;

use App\Models\Character\Fighter;
use App\Models\Character\FighterRepositoryInterface;

/**
 * A different kind of creator for the commom contract
 * used to create an instance from the FighterRepository
 * helps to decide how the Fighter objects are created
 */
class FighterService implements FighterServiceInterface
{
    /**
     * @var FighterRepositoryInterface
     */
    private $fighterRepository;

    public function __construct(FighterRepositoryInterface $fighterRepository)
    {
        $this->fighterRepository = $fighterRepository;
    }

    /**
     * @param string $fighterName
     *
     * @return Fighter
     */
    public function getFighter($fighterName)
    {
        return $this->fighterRepository->getFighter($fighterName);
    }
}
