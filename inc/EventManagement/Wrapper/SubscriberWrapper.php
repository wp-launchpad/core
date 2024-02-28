<?php

namespace LaunchpadCore\EventManagement\Wrapper;

use LaunchpadCore\EventManagement\SubscriberInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class SubscriberWrapper
{

    protected $prefix = '';


    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @param string $prefix
     */
    public function __construct(ContainerInterface $container, string $prefix)
    {
        $this->container = $container;
        $this->prefix = $prefix;
    }

    public function wrap($object): SubscriberInterface
    {
        $methods = get_class_methods($object);
        $reflectionClass = new ReflectionClass(get_class($object));
        $events = [];
        foreach ($methods as $method) {
            $method_reflection = $reflectionClass->getMethod($method);
            $doc_comment = $method_reflection->getDocComment();
            if ( ! $doc_comment ) {
                continue;
            }
            $pattern = "#@hook\s(?<name>[a-zA-Z\\\-_$/]+)(\s(?<priority>[0-9]+))?#";

            preg_match_all($pattern, $doc_comment, $matches, PREG_PATTERN_ORDER);
            if(! $matches) {
                continue;
            }

            foreach ($matches[0] as $index => $match) {
                $hook = str_replace('$prefix', $this->prefix, $matches['name'][$index]);

                $events[$hook][] = [
                    $method,
                    key_exists('priority', $matches) && key_exists($index, $matches['priority']) && $matches['priority'][$index] !== "" ? (int) $matches['priority'][$index] : 10,
                    $method_reflection->getNumberOfParameters(),
                ];
            }
        }

        return new WrappedSubscriber($this->container, $object, $events);
    }
}