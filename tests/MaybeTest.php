<?php

use Duckson\Monads\Optional;

class MaybeMonadTest extends PHPUnit_Framework_TestCase
{
    function testOperationsOnNull() {
        $maybe = new Optional(null);
        $maybe->bind(function($value) {
            self::fail('Bind should not be called on null');
        });
    }

    public function legalValuesProvider() {
        return [
            [true],
            [false],
            [''],
            ['Something'],
            [123],
            [0],
            [1],
            [-1],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider legalValuesProvider
     */
    function testBindOnValue($legalValue) {
        $maybe = new Optional($legalValue);
        $maybe->bind(function($value) use ($legalValue) {
            self::assertNotNull($value);
            self::assertEquals($legalValue, $value);
        });
    }

    function testBindSugarOnValue() {
        $value = new stdClass;
        $value->test = 'test';
        $maybe = new Optional($value);
        $this->assertEquals('test', $maybe->test->value());
    }
}
