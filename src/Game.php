<?php

namespace SimpleCoding;

use SimpleCoding\Core\DependencyInjectionContainer;
use SimpleCoding\Game\Config;
use SimpleCoding\Game\WorldPopulation;

class Game
{
    const OPTION_CONFIG_FILE = 'config_file';

    /**
     * @var DependencyInjectionContainer
     */
    protected $_dic;

    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var WorldPopulation
     */
    protected $_worldPopulation;

    /**
     * @param DependencyInjectionContainer $dic
     */
    public function __construct(
        DependencyInjectionContainer $dic,
        Config $config,
        WorldPopulation $worldPopulation
    ) {
        $this->_dic = $dic;
        $this->_config = $config;
        $this->_worldPopulation = $worldPopulation;
    }

    /**
     * @param array $options
     */
    public function run($options = [])
    {
        if (isset($options[self::OPTION_CONFIG_FILE])) {
            $this->_config->load($options[self::OPTION_CONFIG_FILE]);
        }

        $this->_worldPopulation->populate($this->_config->getLifeForms());

        foreach ($this->_config->getArenas() as $arenaId => $arenaConfig) {
            $arena = $this->_dic->create('SimpleCoding\\Game\\Battle\\Arena', [
                'identifier' => $arenaId,
                'maxTurns' => $arenaConfig['maxTurns'],
            ]);

            foreach ($arenaConfig['battles'] as $battlePlayers) {
                try {
                    foreach ($battlePlayers as $player) {
                        $arena->addPlayer($this->_worldPopulation->getLifeForm($player));
                    }

                    $arena->start();
                    $winner = $arena->getWinner();
                    $message = sprintf('Player %s wins.', $winner);
                    echo $message, "\n";
                    $arena->reset();

                    echo str_repeat('-', strlen($message)), "\n\n";
                } catch (\Exception $e) {
                    echo $e->getMessage(), "\n";
                    // ignore for now
                }
            }
        }
    }
}
