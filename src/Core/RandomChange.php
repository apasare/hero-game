<?php

namespace SimpleCoding\Core;

/**
 * @singleton
 */
class RandomChange
{
    /**
     * @var RandomGenerator
     */
    protected $_randomGenerator;

    public function __construct(RandomGenerator $randomGenerator)
    {
        $this->_randomGenerator = $randomGenerator;
    }

    /**
     * @return int
     */
    public function getChange()
    {
        return $this->_randomGenerator->rand(1, 10000);
    }
}
