<?php

use Duckson\Monads\Identity;
use Duckson\Monads\Optional;
use Duckson\Monads\Many;

class AxiomsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Identity($value)->bind($fn) ==== $fn($value)
     */
    public function testAxiom1()
    {
        $value = 'foo';
        $f = function ($value) {
            return strrev($value);
        };
        $monad = new Identity($value);
        $this->assertEquals($f($value), $monad->bind($f)->value());
    }

    /**
     * $monad->bind(Identity) ==== $monad
     */
    public function testAxiom2()
    {
        $monad = new Identity('foo');
        $identity = function ($value) {
            return (new Identity($value))->value();
        };
        $result = $monad->bind($identity);
        $this->assertEquals($result, $monad);
    }

    /**
     * $monad->bind($f)->bind($g) ==== $monad->bind(function($value) {
     *     return $f($g($value));
     * })
     */
    public function testAxiom3()
    {
        $monad = new Identity('foo');
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
     */
    public function testAxiom4()
    {
        $monad = new Identity('foo');
        $f = 'strrev';
        $g = 'strtoupper';

        $result = $monad->bind($f)->bind($g);
        $other = $monad->bind(function ($value) use ($f, $g) {
            return (new Identity($f($value)))->bind($g)->value();
        });
        $this->assertEquals($result, $other);
    }
}
