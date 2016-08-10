<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Core\RandomChange;
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
     * @var RandomChange
     */
    protected $_randomChange;

    public function __construct(RandomChange $randomChange)
    {
        $this->_randomChange = $randomChange;
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
        return $this->_randomChange->getChange() <= $this->getCastChance();
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
