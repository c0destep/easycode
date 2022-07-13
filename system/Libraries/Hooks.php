<?php

namespace System\Libraries;

class Hooks
{
    private static array $onCallBefore = [];
    private static array $onCallAfter = [];

    /**
     * Add event to run before route calls
     * @param string $class
     * @param string $method
     */
    public static function registerCallBefore(string $class, string $method): void
    {
        self::$onCallBefore[] = array($class, $method);
    }

    /**
     * Add event
     * @param array $events
     */
    public static function registerCallsBefore(array $events): void
    {
        foreach ($events as $class => $method) {
            self::$onCallBefore[] = array($class, $method);
        }
    }

    /**
     * Add event to run after route calls
     * @param string $class
     * @param string $method
     */
    public static function registerCallAfter(string $class, string $method): void
    {
        self::$onCallAfter[] = array($class, $method);
    }

    /**
     * Add event
     * @param array $events
     */
    public static function registerCallsAfter(array $events): void
    {
        foreach ($events as $class => $method) {
            self::$onCallAfter[] = array($class, $method);
        }
    }

    /**
     * Execute event
     */
    public static function executeCallBefore(): void
    {
        foreach (self::$onCallBefore as $item) {
            execute_class($item[0], $item[1]);
        }
    }

    /**
     * Execute event
     */
    public static function executeCallAfter(): void
    {
        foreach (self::$onCallAfter as $item) {
            execute_class($item[0], $item[1]);
        }
    }
}