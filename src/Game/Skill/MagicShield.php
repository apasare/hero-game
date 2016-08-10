<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Game\Battle\Event;
use SimpleCoding\Game\LifeForm;

/**
 * Takes only half of the usual damage when an enemy attacks
 */
class MagicShield extends SkillAbstract
{
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
       return self::TYPE_DEFENSIVE; 
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Magic Shield';
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
        $battleEvent->setDefenderDelta(LifeForm::STAT_HEALTH, $healthDelta / 2);
    }
}
