<?php

namespace LaunchpadCore\Container;

use League\Container\Argument\ClassName;
use League\Container\Argument\ClassNameInterface;
use League\Container\Argument\ClassNameWithOptionalValue;
use League\Container\Argument\RawArgument;
use League\Container\Argument\RawArgumentInterface;
use League\Container\Exception\ContainerException;
use League\Container\Exception\NotFoundException;
use ReflectionFunctionAbstract;
use ReflectionParameter;

class ReflectionContainer extends \League\Container\ReflectionContainer
{
    /**
     * {@inheritdoc}
     */
    public function resolveArguments(array $arguments) : array
    {
        return array_map(function ($argument) {
            $justStringValue = false;

            if ($argument instanceof RawArgumentInterface) {
                return $argument->getValue();
            } elseif ($argument instanceof ClassNameInterface) {
                $id = $argument->getClassName();
            } elseif (!is_string($argument)) {
                return $argument;
            } else {
                $justStringValue = true;
                $id = $argument;
            }

            $container = null;

            try {
                $container = $this->getLeagueContainer();
            } catch (ContainerException $e) {
                if ($this instanceof \League\Container\ReflectionContainer) {
                    $container = $this;
                }
            }

            if ($container !== null) {
                try {
                    return $container->get($id);
                } catch (NotFoundException $exception) {
                    if ($argument instanceof ClassNameWithOptionalValue) {
                        return $argument->getOptionalValue();
                    }

                    if ($justStringValue) {
                        return $id;
                    }

                    throw $exception;
                }
            }

            if ($argument instanceof ClassNameWithOptionalValue) {
                return $argument->getOptionalValue();
            }

            // Just a string value.
            return $id;
        }, $arguments);
    }

    public function reflectArguments(ReflectionFunctionAbstract $method, array $args = []) : array
    {
        $arguments = array_map(function (ReflectionParameter $param) use ($method, $args) {
            $name = $param->getName();
            $type = $param->getType();

            if (array_key_exists($name, $args)) {
                return new RawArgument($args[$name]);
            }

            if ($type) {
                if (PHP_VERSION_ID >= 70100) {
                    $typeName = $type->getName();
                } else {
                    $typeName = (string) $type;
                }

                $typeName = ltrim($typeName, '?');

                if ($param->isDefaultValueAvailable()) {
                    return new ClassNameWithOptionalValue($typeName, $param->getDefaultValue());
                }

                return new ClassName($typeName);
            }

            if ($param->isDefaultValueAvailable()) {
                return new RawArgument($param->getDefaultValue());
            }

            throw new NotFoundException(sprintf(
                'Unable to resolve a value for parameter (%s) in the function/method (%s)',
                $name,
                $method->getName()
            ));
        }, $method->getParameters());

        return $this->resolveArguments($arguments);
    }


}