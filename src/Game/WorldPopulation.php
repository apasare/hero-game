<?php

namespace SimpleCoding\Game;

/**
 * @singleton
 */
class WorldPopulation
{
    /**
     * @var array
     */
    protected $_population = [];

    /**
     * @var LifeFormFactory
     */
    protected $_lifeFormFactory;

    public function __construct(LifeFormFactory $lifeFormFactory)
    {
        $this->_lifeFormFactory = $lifeFormFactory;
    }

    /**
     * @param array $lifeFormsConfig
     * @return void
     */
    public function populate($lifeFormsConfig)
    {
        foreach ($lifeFormsConfig as $id => $lifeFormConfig) {
            try {
                $this->_population[$id] = $this->_lifeFormFactory->spawn($lifeFormConfig);
            } catch (\Exception $e) {
                echo $e->getMessage(), "\n";
                // for now lets just ignore bad lifeform configurations
            }
        }
    }

    /**
     * @param string $key
     * @return LifeForm
     */
    public function getLifeForm($key)
    {
        if (!$this->_population[$key]) {
            throw new \Exception(sprintf('LifeForm %s does not exist in world.', $key));
        }

        return $this->_population[$key];
    }
}
