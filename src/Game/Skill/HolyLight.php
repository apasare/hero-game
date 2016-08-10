<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Game\Battle\Event;
use SimpleCoding\Game\LifeForm;

/**
 * A player can use this spell to heal himself up
 */
class HolyLight extends SkillAbstract
{
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
       return self::TYPE_DEFENSIVE | self::TYPE_OFFENSIVE; 
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Holy Light';
    }

    /**
     * {@inheritDoc}
     */
    public function getCastChance()
    {
        return 1500;
    }

    /**
     * {@inheritDoc}
     */
    public function canCast(Event $battleEvent)
    {
        if ($this->getCaster()->getHealth() >= $this->getCaster()->getMaxHealth()) {
            return false;
        }

        return parent::canCast($battleEvent);
    }

    /**
     * @param Event $battleEvent
     * @return void
     */
    public function cast(Event $battleEvent)
    {
        $healthDelta = $battleEvent->getDelta($this->getCaster(), LifeForm::STAT_HEALTH);
        $battleEvent->setDelta($this->getCaster(), LifeForm::STAT_HEALTH, $healthDelta + 20);
    }
}
