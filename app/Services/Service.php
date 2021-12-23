<?php

namespace App\Services;

class Service
{
    /**
     * Prefix for call static magic method.
     */
    const PREFIX_METHOD = 'do';

    /**
     * @var static Singleton for all services.
     */
    protected static $instances = [];

    /**
     * Method initialization.
     * @return static
     */
    public static function init()
    {
        $serviceName = static::class;

        if (!isset(self::$instances[$serviceName])) {
            self::$instances[$serviceName] = new static();
        }

        return self::$instances[$serviceName];
    }

    /**
     * Alias of method init.
     * @return static
     */
    public static function ready()
    {
        return static::init();
    }

    /**
     * Magic method.
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        static::init();

        $serviceName = static::class;

        $instance = self::$instances[$serviceName];

        $method = $name;

        $method = lcfirst($method);

        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $arguments);
        }

        throw new \BadMethodCallException('Method ' . $name . ' not exists in class ' . static::class . '.');
    }
}
