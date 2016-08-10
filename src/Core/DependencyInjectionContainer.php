<?php

namespace SimpleCoding\Core;

/**
 * just fooling around
 * @singleton
 */
class DependencyInjectionContainer
{
    /**
     * @var array
     */
    protected static $_singletons = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        $hash = self::_getHash(self::class);
        if (!isset(self::$_singletons[$hash])) {
            self::$_singletons[$hash] = new self();
        }

        return self::$_singletons[$hash];
    }

    /**
     * @param \ReflectionClass $class
     * @return boolean
     */
    protected function _isSingleton(\ReflectionClass $class)
    {
        if ($doc = $class->getDocComment()) {
            preg_match_all('#@(.*?)\n#s', $doc, $annotations);
            return in_array('singleton', $annotations[1]);
        }

        return false;
    }

    protected static function _getHash($classTag)
    {
        return md5($classTag);
    }

    /**
     * @param string $objectClass
     * @param array $constructorParams
     * @return object
     */
    public function create($objectClass, array $constructorParams = [])
    {
        $class = new \ReflectionClass($objectClass);
        $constructor = $class->getConstructor();
        if (null === $constructor) {
            return $class->newInstance();
        }

        $noRequiredParams = $constructor->getNumberOfRequiredParameters();
        $parameters = $constructor->getParameters();

        $args = [];
        foreach ($parameters as $index => $param) {
            if (isset($constructorParams[$param->getName()])) {
                $args[$param->getName()] = $constructorParams[$param->getName()];
                continue;
            }

            if ($index >= $noRequiredParams) {
                $args[$param->getName()] = $param->getDefaultValue();
                continue;
            }

            if ($paramClass = $param->getClass()) {
                if ($this->_isSingleton($paramClass)) {
                    $args[$param->getName()] = $this->get($paramClass->getName());
                } else {
                    $args[$param->getName()] = $this->create($paramClass->getName());
                }
            }
        }

        return $class->newInstanceArgs($args);
    }

    /**
     * @param string $objectClass
     * @param array $constructorParams
     * @return object
     */
    public function get($objectClass, array $constructorParams = [])
    {
        $hash = $this->_getHash($objectClass);
        if (!isset(self::$_singletons[$hash])) {
            self::$_singletons[$hash] = $this->create($objectClass, $constructorParams);
        }

        return self::$_singletons[$hash];
    }
}
