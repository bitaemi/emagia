<?php

namespace App\Models\Character;

use App\Models\Character\Fighter;
use App\Models\Character\FighterSkills;
use App\Models\Character\FighterStats;
use App\Models\Skills\SkillInterface;

/**
 * The main class of the common contract for the creation of objects
 * This class will not decide how to instantiate the objects,
 * what parameters to use for instantiation, rather will defer the
 * instantionation to its subclasses - FighterRepository and FighterService
 **/
class FighterFactory implements FighterFactoryInterface
{
    private $defaultConfig = [
        'name'   => 'Fighter',
        'stats'  => [
            'health'   => 0,
            'strength' => 0,
            'defence'  => 0,
            'speed'    => 0,
            'luck'     => 0
        ],
        'skills' => []
    ];

    /**
     * @param string $fighterName
     * @param array  $config
     *
     * @return Fighter
     */
    public function create($fighterName, array $config = [])
    {
        $config = $this->mergeConfigArray($config);

        $fighter = new Fighter;
        $fighter->setName($fighterName);

        $fighterStats = new FighterStats;
        $fighterStats->setHealth($this->generateRandomStatValue($config['stats']['health']));
        $fighterStats->setStrength($this->generateRandomStatValue($config['stats']['strength']));
        $fighterStats->setDefence($this->generateRandomStatValue($config['stats']['defence']));
        $fighterStats->setSpeed($this->generateRandomStatValue($config['stats']['speed']));
        $fighterStats->setLuck($this->generateRandomStatValue($config['stats']['luck']));
        $fighter->setStats($fighterStats);

        $characterSkillsCollection = new FighterSkills;
        foreach ($config['skills'] as $skillClass => $chance) {
            /** @var SkillInterface $skill */
            $skill = new $skillClass($fighter);
            $skill->setChance($chance);
            $characterSkillsCollection->add($skill);
        }

        $fighter->setSkills($characterSkillsCollection);

        $fighter->addListeners();

        return $fighter;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function mergeConfigArray(array $config = [])
    {
        $mergedConfigArrays = array_merge($this->defaultConfig, $config);

        if (array_key_exists('stats', $config)) {
            $mergedConfigArrays['stats'] = array_merge($this->defaultConfig['stats'], $config['stats']);
        }

        return $mergedConfigArrays;
    }

    /**
     * @param array|int $statScale
     *
     * @return int
     */
    private function generateRandomStatValue($statScale)
    {
        if (!is_array($statScale) && is_int($statScale)) {
            return $statScale;
        }

        return rand($statScale[0], $statScale[1]);
    }
}
