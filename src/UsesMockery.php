<?php

namespace Davesweb\MockeryHelper;

use InvalidArgumentException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ReflectionException;
use ReflectionMethod;

trait UsesMockery
{
    use MockeryPHPUnitIntegration;

    /**
     * Determine whether or not to allow the setting of non-existing methods via the method() method.
     *
     * @var bool
     */
    protected $allowNonExistingMethods = false;

    /**
     * Method name helper.
     *
     * If you need to pass a method name to a Mockery expectation, use this method to do so. This method accepts a
     * callable as its argument, assuring that the method name you want to pass is recognised as such by an IDE. That
     * way, when you refactor your code, the method name is also refactored in your Unit tests.
     *
     * This method also checks to see if the method actually exists, so you don't make any typos in your method name.
     *
     * @param callable $callable a callable in the form of 'ClassName::method', [ClassName::class, 'method'] or
     *                           [$object, 'method']
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     *
     * @return string
     */
    public function method($callable): string
    {
        if (is_string($callable) && false !== strstr($callable, '::')) {
            $callable = explode('::', $callable);
        }

        if (!is_array($callable) || empty($callable[0]) || empty($callable[1])) {
            throw new InvalidArgumentException(sprintf('Provided callable is not a valid callable'));
        }

        try {
            $reflection = new ReflectionMethod($callable[0], $callable[1]);

            return $reflection->name;
        } catch (ReflectionException $e) {
            if ($this->allowNonExistingMethods) {
                return $callable[1];
            }

            throw $e;
        }
    }
}
