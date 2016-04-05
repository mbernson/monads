<?php

use Duckson\Monads\Identity;

class IdentityTest extends PHPUnit_Framework_TestCase
{
    public function legalValuesProvider()
    {
        $obj = new stdClass();
        $obj->test = 'test';
        return [
            [true],
            [false],
            [''],
            ['Something'],
            [123],
            [0],
            [1],
            [-1],
            [$obj],
        ];
    }

    /**
     * @dataProvider legalValuesProvider
     */
    public function testBindOnValue($legalValue)
    {
        $identity = new Identity($legalValue);
        $value = $identity->bind(function ($value) use ($legalValue) {
            self::assertNotNull($value);
            self::assertEquals($legalValue, $value);
            return $value;
        })->value();
        $this->assertEquals($legalValue, $value);
    }

    /**
     * @dataProvider legalValuesProvider
     */
    public function testBindSugarOnValue($value)
    {
        $identity = new Identity($value);
        $this->assertEquals($value, $identity->value());
    }

    public function testMultipleBindSugarOnValue()
    {
        $value = new stdClass;
        $value->test = 'test';
        $identity = new Identity($value);
        $this->assertEquals('test', $identity->test->value());
    }
}
