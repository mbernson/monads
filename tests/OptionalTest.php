<?php

use Duckson\Monads\Optional;

class OptionalTest extends PHPUnit_Framework_TestCase
{
    public function testOperationsOnNull()
    {
        $maybe = new Optional(null);
        $maybe->bind(function ($value) {
            self::fail('Bind should never be called on null');
        });
    }

    public function legalValuesProvider()
    {
        return [
            [true],
            [false],
            [''],
            ['Something'],
            ['null'],
            [123],
            [0],
            [1],
            [-1],
            [[1, 'a', false]],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider legalValuesProvider
     */
    public function testBindOnValue($legalValue)
    {
        $maybe = new Optional($legalValue);
        $maybe->bind(function ($value) use ($legalValue) {
            self::assertNotNull($value);
            self::assertEquals($legalValue, $value);
        });
    }

    /**
     * @dataProvider legalValuesProvider
     */
    public function testBindSugarOnValue($legalValue)
    {
        $value = new stdClass;
        $value->test = $legalValue;
        $maybe = new Optional($value);
        $this->assertEquals($legalValue, $maybe->test->value());
    }

    /**
     * @dataProvider legalValuesProvider
     */
    public function testUnwrappingMultipleMonads($legalValue)
    {
        $m = new Optional(new Optional(new Optional($legalValue)));
        $this->assertEquals($legalValue, $m->value());
    }
}
