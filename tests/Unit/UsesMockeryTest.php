<?php

namespace Tests\MockeryHelper\Unit\UsesMockeryTest;

use Davesweb\MockeryHelper\Tests\Stubs\StubClass;
use Davesweb\MockeryHelper\UsesMockery;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class UsesMockeryTest extends TestCase
{
    use UsesMockery;

    /**
     * @dataProvider validCallableProvider
     *
     * @param callable $callable
     * @param string   $expectedMethod
     */
    public function test_method_with_valid_callable_returns_method_name($callable, string $expectedMethod)
    {
        $actualMethod = $this->method($callable);

        $this->assertEquals($expectedMethod, $actualMethod);
    }

    /**
     * @dataProvider invalidCallableProvider
     *
     * @param callable $callable
     * @param string   $expectedException
     */
    public function test_method_with_invalid_callable_fails($callable, string $expectedException)
    {
        $this->expectException($expectedException);

        $this->method($callable);
    }

    /**
     * @dataProvider nonExistingMethodCallableProvider
     *
     * @param callable $callable
     * @param string   $expectedMethod
     */
    public function test_method_with_non_existing_function_returns_method_name_if_settings_set(
        $callable,
        string $expectedMethod
    ) {
        $this->allowNonExistingMethods = true;

        $actualMethod = $this->method($callable);

        $this->assertEquals($expectedMethod, $actualMethod);
    }

    /**
     * @return array
     */
    public function validCallableProvider(): array
    {
        $stubReflection = new ReflectionClass(StubClass::class);
        $methods        = $stubReflection->getMethods();

        $stubObject = new StubClass();

        $data = [];

        foreach ($methods as $method) {
            $data[] = [[$stubObject, $method->name], $method->name];
            $data[] = [[StubClass::class, $method->name], $method->name];
            $data[] = [
                sprintf('\\Davesweb\\MockeryHelper\\Tests\\Stubs\\StubClass::%s', $method->name),
                $method->name,
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function invalidCallableProvider(): array
    {
        $object = new StubClass();

        return [
            ['SomeNonExistingClass::method', ReflectionException::class],
            ['WrongSyntax', InvalidArgumentException::class],
            ['', InvalidArgumentException::class],
            [null, InvalidArgumentException::class],
            ['\\Davesweb\\MockeryHelper\\Tests\\Stubs\\StubClass::someMethod', ReflectionException::class],
            [['\\Davesweb\\MockeryHelper\\Tests\\Stubs\\StubClass', 'someMethod'], ReflectionException::class],
            [[$object, 'someMethod'], ReflectionException::class],
        ];
    }

    /**
     * @return array
     */
    public function nonExistingMethodCallableProvider(): array
    {
        $object = new StubClass();

        return [
            [[$object, 'someMethod'], 'someMethod'],
            [['\\Davesweb\\MockeryHelper\\Tests\\Stubs\\StubClass', 'someMethod'], 'someMethod'],
            ['\\Davesweb\\MockeryHelper\\Tests\\Stubs\\StubClass::someMethod', 'someMethod'],
        ];
    }
}
