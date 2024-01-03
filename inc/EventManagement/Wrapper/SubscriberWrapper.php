<?php

namespace LaunchpadCore\EventManagement\Wrapper;

use LaunchpadCore\EventManagement\SubscriberInterface;
use ReflectionClass;

class SubscriberWrapper
{
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
            $pattern = "@hook\s(?<name>[a-zA-/Z-_$]+)(\s(?<priority>[0-9]+))?";

            preg_match_all($pattern, $doc_comment, $matches, PREG_PATTERN_ORDER);

            if(! $matches) {
                continue;
            }

            foreach ($matches as $match) {
                $events[$match['name']][] = [
                  $method,
                  $match['priority']?: 10,
                  $method_reflection->getNumberOfParameters(),
                ];
            }
        }

        return new WrappedSubscriber($object, $events);
    }
}