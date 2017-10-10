<?php

namespace Tests\MockeryHelper\Unit\UsesMockeryTest;

use Davesweb\MockeryHelper\Tests\Stubs\StubClass;
use Davesweb\MockeryHelper\UsesMockery;
use InvalidArgumentException;
use Mockery\MockInterface;
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

    public function test_mock_returns_mocked_object()
    {
        $mockedObject = $this->mock(StubClass::class);

        $this->assertInstanceOf(MockInterface::class, $mockedObject);
    }

    public function test_spy_returns_mocked_object()
    {
        $mockedObject = $this->spy(StubClass::class);

        $this->assertInstanceOf(MockInterface::class, $mockedObject);
    }

    public function test_spy_returns_mocked_object_which_ignores_missing()
    {
        $mockedObject = $this->spy(StubClass::class);

        $mockedObject->someNonExistingMethod();

        $this->assertInstanceOf(MockInterface::class, $mockedObject);
    }

    public function test_named_mock_returns_mocked_object_with_name()
    {
        $mockedObject = $this->namedMock('Stub', StubClass::class);

        $this->assertInstanceOf(MockInterface::class, $mockedObject);
        $this->assertEquals('Stub', $mockedObject->mockery_getName());
    }

    public function test_method_uses_global_check_settings_allow_non_existing_is_true()
    {
        $this->allowNonExistingMethods = true;

        $actualMethod = $this->method([StubClass::class, 'someNoneExistingMethod'], null);

        $this->assertEquals('someNoneExistingMethod', $actualMethod);
    }

    public function test_method_uses_global_check_settings_allow_non_existing_is_false()
    {
        $this->allowNonExistingMethods = false;

        $this->expectException(ReflectionException::class);

        $this->method([StubClass::class, 'someNoneExistingMethod'], null);
    }

    public function test_method_overwrites_global_check_settings_for_check_is_true()
    {
        $this->allowNonExistingMethods = true;

        $this->expectException(ReflectionException::class);

        $this->method([StubClass::class, 'someNoneExistingMethod'], true);
    }

    public function test_method_overwrites_global_check_settings_for_check_is_false()
    {
        $this->allowNonExistingMethods = false;

        $actualMethod = $this->method([StubClass::class, 'someNoneExistingMethod'], false);

        $this->assertEquals('someNoneExistingMethod', $actualMethod);
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
