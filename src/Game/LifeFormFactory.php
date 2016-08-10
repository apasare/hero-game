<?php

namespace SimpleCoding\Game;

use SimpleCoding\Core\DependencyInjectionContainer;
use SimpleCoding\Core\RandomGenerator;

/**
 * @singleton
 */
class LifeFormFactory
{
    /**
     * @var DependencyInjectionContainer
     */
    protected $_dic;

    /**
     * @var RandomGenerator
     */
    protected $_randomGenerator;

    /**
     * @param DependencyInjectionContainer $dic
     */
    public function __construct(
        DependencyInjectionContainer $dic,
        RandomGenerator $randomGenerator
    ) {
        $this->_dic = $dic;
        $this->_randomGenerator = $randomGenerator;
    }

    /**
     * @param array $statsConfig
     * @return array
     */
    protected function _prepareStats($statsConfig)
    {
        $stats = [];
        foreach ($statsConfig as $key => $value) {
            if (is_array($value)) {
                $value = $this
                    ->_randomGenerator
                    ->rand($value['min'], $value['max'])
                ;
            }

            $stats[$key] = (int) $value;
        }
        $stats[LifeForm::STAT_MAX_HEALTH] = $stats[LifeForm::STAT_HEALTH];

        return $stats;
    }

    /**
     * @param LifeForm $lifeForm
     * @param \SimpleCoding\Game\Skill\SkillAbstract[] $skills
     */
    protected function _addSkills(LifeForm $lifeForm, $skills)
    {
        foreach ($skills as $skill) {
            try {
                $lifeForm->addSkill(
                    $this->_dic->create($skill)
                );
            } catch (\Exception $e) {
                echo $e->getMessage(), "\n";
                // for now ignore invalid skills configuration
            }
        }
    }

    /**
     * @param array $lifeFormConfig
     * @return void
     */
    public function spawn($lifeFormConfig)
    {
        $lifeForm = $this->_dic->create($lifeFormConfig['type'], [
            'name' => $lifeFormConfig['name'],
            'stats' => $this->_prepareStats($lifeFormConfig['stats'])
        ]);

        if (!($lifeForm instanceof LifeForm)) {
            throw new \Exception('Invalid LifeForm type.');
        }

        if (is_array($lifeFormConfig['skills'])) {
            $this->_addSkills($lifeForm, $lifeFormConfig['skills']);
        }

        return $lifeForm;
    }
}
