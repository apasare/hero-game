<?php

namespace SimpleCoding\Game\Battle;

use SimpleCoding\Game\LifeForm;

class Arena
{
    /**
     * @var int
     */
    protected $_maxTurns;

    /**
     * @var string
     */
    protected $_identifier;

    /**
     * @var LifeForm[]
     */
    protected $_players = [];

    /**
     * @var Engine
     */
    protected $_battleEngine;

    /**
     * @param Engine $battleEngine
     * @param string $identifier
     * @param int $maxTurns
     */
    public function __construct(Engine $battleEngine, $identifier, $maxTurns)
    {
        $this->_battleEngine = $battleEngine;
        $this->_identifier = $identifier;
        $this->_maxTurns = $maxTurns;
    }

    /**
     * @return int
     */
    protected function _findFirstAttacker()
    {
        if ($this->_players[0]->getSpeed() == $this->_players[1]->getSpeed()) {
            return $this->_players[0]->getLuck() >= $this->_players[1]->getLuck() ? 0 : 1;
        }

        return $this->_players[0]->getSpeed() > $this->_players[1]->getSpeed() ? 0 : 1;
    }

    /**
     * @return LifeForm
     */
    public function getWinner()
    {
        if ($this->_players[0]->getHealth() == $this->_players[1]->getHealth()) {
            return $this->_players[0]->getLuck() >= $this->_players[1]->getLuck() ? $this->_players[0] : $this->_players[1];
        }

        return $this->_players[0]->getHealth() > $this->_players[1]->getHealth() ? $this->_players[0] : $this->_players[1];

    }

    /**
     * @param LifeForm $player
     * @return Arena
     */
    public function addPlayer(LifeForm $player)
    {
        if (count($this->_players) == 2) {
            throw new \Exception('Only two players are allowed in arena.');
        }

        $this->_players[] = $player;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxTurns()
    {
        return $this->_maxTurns;
    }

    /**
     * @return void
     */
    public function start()
    {
        if (count($this->_players) < 2) {
            throw new \Exception('You need two players to start a battle in arena.');
        }

        $attackerIndex = $this->_findFirstAttacker();
        $attacker = $this->_players[$attackerIndex];
        $defender = $this->_players[++$attackerIndex % 2];

        $message = sprintf('New battle: %s vs %s', $attacker, $defender);
        echo $message, "\n\n";

        $this->_battleEngine->fight($this, $attacker, $defender);
    }

    /**
     * @return void
     */
    public function reset()
    {
        foreach ($this->_players as $player) {
            $player->reset();
        }

        $this->_players = [];
    }
}
