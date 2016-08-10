<?php

namespace SimpleCoding\Game\Skill;

use SimpleCoding\Game\Battle\Event;
use SimpleCoding\Game\LifeForm;

/**
 * When a player uses this spell to defend himself,
 * the same damage which applies to him will also be applied to attacker
 */
class EyeForEye extends SkillAbstract
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
        return 'Eye For Eye';
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
        $battleEvent->setAttackerDelta(LifeForm::STAT_HEALTH, $healthDelta);
    }

}
