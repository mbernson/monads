<?php

use Duckson\Monads\Many;

final class Person
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

class ManyTest extends PHPUnit_Framework_TestCase
{
    private $people;

    protected function setUp()
    {
        parent::setUp();
        $this->people = [
            new Person('Jip'),
            new Person('Janneke'),
            new Person('Mies'),
            new Person('Piet'),
            new Person('Klaas'),
        ];
    }

    public function testManyBind()
    {
        $many = new Many($this->people);
        $names = $many->bind(function ($person) {
            return $person->getName();
        })->value();

        $this->assertTrue(is_array($names));
        $this->assertEquals(5, count($names));
        $this->assertEquals('Jip', $names[0]);
        $this->assertEquals('Klaas', $names[4]);
    }

    public function testManyBindSugar()
    {
        $many = new Many($this->people);
        $names = $many->getName()->value();

        $this->assertTrue(is_array($names));
        $this->assertEquals(5, count($names));
        $this->assertEquals('Jip', $names[0]);
        $this->assertEquals('Klaas', $names[4]);
    }
}
