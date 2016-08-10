<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Game\Battle\Event;
use SimpleCoding\Game\LifeForm;

/**
 * The damage is reflected back to attacker
 */
class SpellReflect extends SkillAbstract
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
        return 'Spell Reflect';
    }

    /**
     * {@inheritDoc}
     */
    public function getCastChance()
    {
        return 1000;
    }

    /**
     * {@inheritDoc}
     */
    public function canCast(Event $battleEvent)
    {
        if (!$battleEvent->getDefenderDelta(LifeForm::STAT_HEALTH)) {
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
        $healthDelta = $battleEvent->getDefenderDelta(LifeForm::STAT_HEALTH);
        $battleEvent->setDefenderDelta(LifeForm::STAT_HEALTH, 0);
        $battleEvent->setAttackerDelta(LifeForm::STAT_HEALTH, $healthDelta);
    }

}
