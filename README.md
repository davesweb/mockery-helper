# Mockery Helper
A library to help you use [Mockery](https://github.com/mockery/mockery) in the best way possible.

[![Build Status](https://travis-ci.org/davesweb/mockery-helper.svg?branch=master)](https://travis-ci.org/davesweb/mockery-helper)
[![Latest Stable Version](https://poser.pugx.org/davesweb/mockery-helper/v/stable)](https://packagist.org/packages/davesweb/mockery-helper)
[![Latest Unstable Version](https://poser.pugx.org/davesweb/mockery-helper/v/unstable)](https://packagist.org/packages/davesweb/mockery-helper)
[![License](https://poser.pugx.org/davesweb/mockery-helper/license)](https://packagist.org/packages/davesweb/mockery-helper)
[![composer.lock](https://poser.pugx.org/davesweb/mockery-helper/composerlock)](https://packagist.org/packages/davesweb/mockery-helper)

## Installation

_Via composer:_

```
composer require davesweb/mockery-helper:dev-master
```

Use the `--dev` option if you only require your test dependencies in developer mode.

_Via composer.json file:_

You can also add this package directly to your `composer.json` file. Add the following line 
to your `require` block, or `require-dev` block:

```
"davesweb/mockery-helper": "dev-master"
```

Than run: 
```
composer update davesweb/mockery-helper
``` 

## Usage

Once you installed the package, you can use the provided trait in your tests:

```php
<?php

namespace My\Tests;

use Davesweb\MockeryHelper\UsesMockery;
use PHPUnit\Framework\TestCase;

class UsesMockeryTest extends TestCase
{
    use UsesMockery;
}
```

This traits ensures that your Mockery expectations are enforced, because it expands on the 
`Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration` trait. So if you use this trait, calling 
`\Mockery::close()` is no longer needed.

### Methods

This trait provides the following methods.

__method__

Signature:
```php
public function method(callable $callable): string
```

If you need to pass a method name to a Mockery expectation, use this method to do so. This method 
accepts a callable as its argument, assuring that the method name you want to pass is recognised 
as such by an IDE. That way, when you refactor your code, the method name is also refactored in your 
Unit tests.

This method also checks to see if the method actually exists, so you don't make any typos in your 
method name.

The callable can be passed in the form of `'ClassName::method'`, `[ClassName::class, 'method']` or
`[$object, 'method']`.

The `method` method returns the name of the method.

Example usage:

```php
<?php

namespace My\Tests;

use Davesweb\MockeryHelper\UsesMockery;
use PHPUnit\Framework\TestCase;

class UsesMockeryTest extends TestCase
{
    use UsesMockery;
    
    public function setUp()
    {
        $mockedDependency = \Mockery::mock(MyDependency::class);
        
        $mockedDependency
            ->shouldReceive($this->method([MyDependency::class, 'methodItShouldReceive']))
            ->with('param 1', 'param 2')
            ->once()
            ->andReturn('My return value');
        
        //...
    }
}
```
 
