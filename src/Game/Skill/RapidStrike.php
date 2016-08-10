<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Game\Battle\Event;
use SimpleCoding\Game\LifeForm;

/**
 * Strike twice while it’s his turn to attack
 */
class RapidStrike extends SkillAbstract
{
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
       return self::TYPE_OFFENSIVE; 
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Rapid Strike';
    }

    /**
     * {@inheritDoc}
     */
    public function getCastChance()
    {
        return 1000;
    }

    /**
     * @param Event $battleEvent
     * @return void
     */
    public function cast(Event $battleEvent)
    {
        $healthDelta = $battleEvent->getDefenderDelta(LifeForm::STAT_HEALTH);
        $battleEvent->setDefenderDelta(LifeForm::STAT_HEALTH, $healthDelta * 2);
    }
}
