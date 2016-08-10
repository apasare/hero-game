<?php

namespace SimpleCoding\Game;

use Symfony\Component\Yaml\Yaml;

/**
 * @singleton
 */
class Config
{
    /**
     * @var array
     */
    protected $_config;

    /**
     * @param string $file
     * @return Config
     */
    public function load($file)
    {
        $this->_config = Yaml::parse(file_get_contents($file));
        return $this;
    }

    /**
     * @return array
     */
    public function getLifeForms()
    {
        return $this->_config['lifeforms'];
    }

    /**
     * @return array
     */
    public function getArenas()
    {
        return $this->_config['arenas'];
    }
}
