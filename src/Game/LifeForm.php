<?php

namespace SimpleCoding\Game;

use SimpleCoding\Game\Skill\SkillAbstract;
use SimpleCoding\Core\RandomChange;

abstract class LifeForm
{
    const STAT_MAX_HEALTH = 'max_health';
    const STAT_HEALTH = 'health';
    const STAT_STRENGTH = 'strength';
    const STAT_DEFENCE = 'defence';
    const STAT_SPEED = 'speed';
    const STAT_LUCK = 'luck';

    protected $_availableStats = [
        self::STAT_MAX_HEALTH,
        self::STAT_HEALTH,
        self::STAT_STRENGTH,
        self::STAT_DEFENCE,
        self::STAT_SPEED,
        self::STAT_LUCK,
    ];

    /**
     * @var array
     */
    protected $_stats = [];

    /**
     * @var array
     */
    protected $_origStats = [];

    /**
     * @var array
     */
    protected $_skills = [];

    /**
     * @var RandomChange
     */
    protected $_randomChange;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @param int $maxHealth
     * @param array $stats
     */
    public function __construct(RandomChange $randomChange, $name, $stats)
    {
        $this->_randomChange = $randomChange;
        $this->_name = $name;

        foreach ($this->_availableStats as $statKey) {
            if (!isset($stats[$statKey])) {
                continue;
            }

            $this->_origStats[$statKey] = (int) $stats[$statKey];
        }

        $this->reset();
    }

    protected function _fixStatsLimits()
    {
        if ($this->_stats[self::STAT_HEALTH] > $this->_stats[self::STAT_MAX_HEALTH]) {
            $this->_stats[self::STAT_HEALTH] = $this->_stats[self::STAT_MAX_HEALTH];
        } else if ($this->_stats[self::STAT_HEALTH] < 0) {
            $this->_stats[self::STAT_HEALTH] = 0;
        }

        if ($this->_stats[self::STAT_LUCK] > 100) {
            $this->_stats[self::STAT_LUCK] = 100;
        } else if ($this->_stats[self::STAT_LUCK] < 0) {
            $this->_stats[self::STAT_LUCK] = 0;
        }
    }

    public function reset()
    {
        $this->_stats = $this->_origStats;
        $this->_fixStatsLimits();
    }

    /**
     * @return boolean
     */
    public function isLucky()
    {
        return $this->_randomChange->getChange() <= (int) ($this->getLuck() * 100);
    }

    /**
     * @param array $deltas
     */
    public function applyDeltas(array $deltas)
    {
        foreach ($this->_availableStats as $statKey) {
            if (!isset($deltas[$statKey])) {
                continue;
            }

            $this->_stats[$statKey] += (int) $deltas[$statKey];
        }

        $this->_fixStatsLimits();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxHealth()
    {
        return $this->_stats[self::STAT_MAX_HEALTH];
    }

    /**
     * {@inheritDoc}
     */
    public function getHealth()
    {
        return $this->_stats[self::STAT_HEALTH];
    }

    /**
     * {@inheritDoc}
     */
    public function getStrength()
    {
        return $this->_stats[self::STAT_STRENGTH];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefence()
    {
        return $this->_stats[self::STAT_DEFENCE];
    }

    /**
     * {@inheritDoc}
     */
    public function getSpeed()
    {
        return $this->_stats[self::STAT_SPEED];
    }

    /**
     * {@inheritDoc}
     */
    public function getLuck()
    {
        return $this->_stats[self::STAT_LUCK];
    }

    /**
     * @param SkillAbstract $skill
     * @return LifeForm
     */
    public function addSkill(SkillAbstract $skill)
    {
        $skill->setCaster($this);
        $this->_skills[] = $skill;
        return $this;
    }

    /**
     * @return SkillAbstract[]
     */
    public function getOffensiveSkills()
    {
        return array_filter($this->_skills, function (SkillAbstract $skill) {
            return $skill->getType() & SkillAbstract::TYPE_OFFENSIVE;
        });
    }

    /**
     * @return SkillAbstract[]
     */
    public function getDefensiveSkills()
    {
        return array_filter($this->_skills, function (SkillAbstract $skill) {
            return $skill->getType() & SkillAbstract::TYPE_DEFENSIVE;
        });
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('"%s" (HP: %d)', $this->getName(), $this->getHealth());
    }
}
