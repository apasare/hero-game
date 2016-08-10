<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Core\RandomGenerator;
use SimpleCoding\Game\Battle\Event;
use SimpleCoding\Game\LifeForm;

abstract class SkillAbstract
{
    const TYPE_OFFENSIVE = 1;
    const TYPE_DEFENSIVE = 2;

    /**
     * @var int
     */
    protected $_castChance;

    /**
     * @var LifeForm
     */
    protected $_caster;

    /**
     * @var RandomGenerator
     */
    protected $_randomGenerator;

    public function __construct(RandomGenerator $randomGenerator)
    {
        $this->_randomGenerator = $randomGenerator;
    }

    /**
     * @return int
     */
    protected function _getRandomChange()
    {
        return $this->_randomGenerator->rand(1, 10000);
    }

    /**
     * return type bit mask
     * e.g.: TYPE_OFFENSIZE | TYPE_DEFENSIVE
     *
     * @return int
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return int
     */
    abstract public function getCastChance();

    /**
     * @param Event $battleEvent
     * @return bool
     */
    public function canCast(Event $battleEvent)
    {
        return $this->_getRandomChange() <= $this->getCastChance();
    }

    abstract public function cast(Event $battleEvent);

    public function setCaster(LifeForm $caster)
    {
        $this->_caster = $caster;
    }

    public function getCaster()
    {
        return $this->_caster;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
