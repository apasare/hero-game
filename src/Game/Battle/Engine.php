<?php

namespace SimpleCoding\Game\Battle;

use SimpleCoding\Core\DependencyInjectionContainer;
use SimpleCoding\Game\LifeForm;

/**
 * @singleton
 */
class Engine
{
    /**
     * @var DependencyInjectionContainer
     */
    protected $_dic;

    /**
     * @param DependencyInjectionContainer $dic
     */
    public function __construct(DependencyInjectionContainer $dic)
    {
        $this->_dic = $dic;
    }

    /**
     * @param LifeForm $attacker
     * @param LifeForm $defender
     * @return int
     */
    protected function _getBaseDamage(LifeForm $attacker, LifeForm $defender)
    {
        $dmg = $attacker->getStrength() - $defender->getDefence();

        return $dmg > 0 ? $dmg : 0;
    }

    /**
     * @param array $skills
     * @param Event $battleEvent
     */
    protected function _castSkills(array $skills, Event $battleEvent)
    {
        foreach ($skills as $skill) {
            if ($skill->canCast($battleEvent)) {
                $skill->cast($battleEvent);
                $message = sprintf(
                    '%s used "%s"',
                    $skill->getCaster(),
                    $skill
                );
                echo $message, "\n";

                // allow only one skill per type to be used for now
                break;
            }
        }
    }

    protected function _outputHpDeltaMessage($lifeForm, $hpDelta)
    {
        if (!$hpDelta) {
            return;
        }

        $message = sprintf(
            '%s %s %.2f damage',
            $lifeForm,
            $hpDelta < 0 ? 'takes' : 'heals',
            abs($hpDelta)
        );

        echo $message, "\n";
    }

    /**
     * @param Arena $arena
     * @param LifeForm $attacker
     * @param LifeForm $defender
     * @param int $turn
     * @return void
     */
    public function fight(Arena $arena, LifeForm $attacker, LifeForm $defender, $turn = 1)
    {
        $baseDamage = $this->_getBaseDamage($attacker, $defender);
        $battleEvent = $this->_dic->create('SimpleCoding\\Game\\Battle\\Event', [
            'attacker' => $attacker,
            'defender' => $defender,
            'damage' => $baseDamage
        ]);

        echo sprintf("Round %d\n%s attacks %s\n", $turn, $attacker, $defender);

        $this->_castSkills($attacker->getOffensiveSkills(), $battleEvent);
        $this->_castSkills($defender->getDefensiveSkills(), $battleEvent);

        $defenderHpDelta = $battleEvent->getDefenderDelta(LifeForm::STAT_HEALTH);
        $this->_outputHpDeltaMessage($defender, $defenderHpDelta);
        $attackerHpDelta = $battleEvent->getAttackerDelta(LifeForm::STAT_HEALTH);
        $this->_outputHpDeltaMessage($attacker, $attackerHpDelta);
        echo "\n";
        // $message = sprintf(
        //     "%s %s %.2f damage\n%s %s %.2f damage\n\n",
        //     $defender,
        //     $defenderHpDelta < 0 ? 'takes' : 'heals',
        //     ($defenderHpDelta),
        //     $attacker,
        //     $attackerHpDelta < 0 ? 'takes' : 'heals',
        //     ($attackerHpDelta)
        // );
        // echo $message;

        $attacker->applyDeltas($battleEvent->getAttackerDeltas());
        $defender->applyDeltas($battleEvent->getDefenderDeltas());

        if (
            $turn == $arena->getMaxTurns()
            || $attacker->getHealth() <= 0
            || $defender->getHealth() <= 0
        ) {
            return;
        }

        $this->fight($arena, $defender, $attacker, ++$turn);
    }
}
