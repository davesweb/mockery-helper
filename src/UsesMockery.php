<?php

namespace Davesweb\MockeryHelper;

use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
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
     * @param callable $callable   a callable in the form of 'ClassName::method', [ClassName::class, 'method'] or
     *                             [$object, 'method']
     * @param bool     $testMethod Whether or not to check if the method actually exists on the object. Set to null to use
     *                             the global setting.
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     *
     * @return string
     */
    public function method($callable, bool $testMethod = null): string
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
            if ((null === $testMethod && $this->allowNonExistingMethods) || false === $testMethod) {
                return $callable[1];
            }

            throw $e;
        }
    }

    /**
     * @param array ...$args
     *
     * @return MockInterface
     */
    public function mock(...$args): MockInterface
    {
        return call_user_func_array([Mockery::class, 'mock'], $args);
    }

    /**
     * @param array ...$args
     *
     * @return MockInterface
     */
    public function spy(...$args): MockInterface
    {
        return call_user_func_array([Mockery::class, 'spy'], $args);
    }

    /**
     * @param array ...$args
     *
     * @return MockInterface
     */
    public function namedMock(...$args): MockInterface
    {
        return call_user_func_array([Mockery::class, 'namedMock'], $args);
    }
}
