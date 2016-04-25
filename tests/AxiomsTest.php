<?php

use Duckson\Monads\Monad;

class AxiomsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Identity($value)->bind($fn) ==== $fn($value)
     *
     * @dataProvider monadTypesProvider
     */
    public function testAxiom1($class, $value)
    {
        $fn = function ($value) {
            return strrev($value);
        };
        /** @var Monad $monad */
        $monad = new $class($value);
        $this->assertEquals($fn($value), $monad->bind($fn)->value());

        $value = 'foo';
        $fn = 'strrev';
        $monad = new $class($value);
        $this->assertEquals($fn($value), $monad->bind($fn)->value());
    }

    /**
     * $monad->bind($monad) ==== $monad
     *
     * @dataProvider monadTypesProvider
     */
    public function testAxiom2($class, $value)
    {
        /** @var Monad $monad */
        $monad = new $class($value);
        $identity = function ($value) use ($class) {
            return (new $class($value))->value();
        };
        $result = $monad->bind($identity);
        $this->assertEquals($result, $monad);
    }

    /**
     * $monad->bind($f)->bind($g) ==== $monad->bind(function($value) {
     *     return $f($g($value));
     * })
     *
     * @dataProvider monadTypesProvider
     */
    public function testAxiom3($class, $value)
    {
        /** @var Monad $monad */
        $monad = new $class($value);
        $f = 'strrev';
        $g = 'strtoupper';

        $result = $monad->bind($f)->bind($g);
        $other = $monad->bind(function ($value) use ($f, $g) {
            return $f($g($value));
        });
        $this->assertEquals($result, $other);
    }

    /**
     * $monad->bind($f)->bind($g) ==== $monad->bind(function($value) {
     *     return new self($value)->bind($g)->value();
     * })
     *
     * @dataProvider monadTypesProvider
     */
    public function testAxiom4($class, $value)
    {
        /** @var Monad $monad */
        $monad = new $class($value);
        $f = 'strrev';
        $g = 'strtoupper';

        $result = $monad->bind($f)->bind($g);
        $other = $monad->bind(function ($value) use ($f, $g, $class) {
            /** @var Monad $monad */
            $monad = new $class($f($value));
            return $monad->bind($g)->value();
        });
        $this->assertEquals($result, $other);
    }

    public function monadTypesProvider()
    {
        return [
            ['Duckson\Monads\Identity', 'foo'],
            ['Duckson\Monads\Optional', 'foo'],
            // ['Duckson\Monads\Many', ['foo']], // Needs adaptation because it always uses arrays
        ];
    }

    /**
     * @dataProvider monadTypesProvider
     */
    public function testUnwrappingMultipleMonads($class, $value)
    {
        /** @var Monad $monad */
        $monad = new $class(new $class(new $class($value)));
        $this->assertEquals($value, $monad->value());
    }
}
