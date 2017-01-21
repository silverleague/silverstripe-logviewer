<?php

namespace SilverLeague\LogViewer\Tests\Helper;


use ReflectionClass;
use SilverLeague\LogViewer\Helper\DeprecatedLogFormatter;
use SilverStripe\ORM\DataObject;

/**
 * Class Invoker helps by invoking protected or private methods for testing.
 * @package SilverLeague\LogViewer\Tests\Helper
 *
 * @author Simon Erkelens <simon@casa-laguna.net>
 */
class Invoker
{

    /**
     * Call protected/private method of a class.
     *
     * @param DataObject|DeprecatedLogFormatter &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public static function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}