<?php

namespace SimpleCoding\Game\Battle;

use SimpleCoding\Game\LifeForm;

class Event
{
    /**
     * @var LifeForm
     */
    protected $_attacker;

    /**
     * @var LifeForm
     */
    protected $_defender;

    /**
     * @var array
     */
    protected $_deltas = [];

    /**
     * @param LifeForm $attacker
     * @param LifeForm $defender
     * @param int $damage
     */
    public function __construct(LifeForm $attacker, LifeForm $defender, $damage)
    {
        $this->_attacker = $attacker;
        $this->_attackerHash = spl_object_hash($this->_attacker);
        $this->_deltas[$this->_attackerHash] = [];

        $this->_defender = $defender;
        $this->_defenderHash = spl_object_hash($this->_defender);
        $this->_deltas[$this->_defenderHash] = [];

        $this->setDefenderDelta(LifeForm::STAT_HEALTH, -$damage);
    }

    /**
     * @return LifeForm
     */
    public function getAttacker()
    {
        return $_attacker;
    }

    /**
     * @return LifeForm
     */
    public function getDefender()
    {
        return $_defender;
    }

    /**
     * @param LifeForm $caster [description]
     * @param string $key
     * @return int
     */
    public function getDelta(LifeForm $caster, $key)
    {
        $casterHash = spl_object_hash($caster);
        if (isset($this->_deltas[$casterHash])) {
            return $this->_deltas[$casterHash][$key];
        }
        return null;
    }

    /**
     * @param LifeForm $caster [description]
     * @param string $key
     * @param int $value
     * @return Event
     */
    public function setDelta(LifeForm $caster, $key, $value)
    {
        $casterHash = spl_object_hash($caster);
        if (!isset($this->_deltas[$casterHash])) {
            throw new \Exception(sprintf(
                'Player %s does not participate in this battle event.', $caster
            ));
        }
        $this->_deltas[$casterHash][$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return int
     */
    public function getAttackerDelta($key)
    {
        return $this->_deltas[$this->_attackerHash][$key];
    }

    /**
     * @param string $key
     * @param int $value
     * @return Event
     */
    public function setAttackerDelta($key, $value)
    {
        $this->_deltas[$this->_attackerHash][$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return int
     */
    public function getDefenderDelta($key)
    {
        return $this->_deltas[$this->_defenderHash][$key];
    }

    /**
     * @param string $key
     * @param int $value
     */
    public function setDefenderDelta($key, $value)
    {
        $this->_deltas[$this->_defenderHash][$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttackerDeltas()
    {
        return $this->_deltas[$this->_attackerHash];
    }

    /**
     * @return array
     */
    public function getDefenderDeltas()
    {
        return $this->_deltas[$this->_defenderHash];
    }
}
