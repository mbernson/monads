<?php namespace Duckson\Monads;

function array_flatten($arg) {
    if(is_array($arg)) {
        return array_reduce($arg, function ($collector, $element) {
            return array_merge($collector, array_flatten($element));
        },[]);
    } else {
        return [$arg];
    }
}

class Many implements Monad {
    protected $values;

    public function __construct(array $values) {
        $this->values = $values;
    }

    public function bind(callable $fn) {
        return new Many(array_flatten(array_map($fn, $this->values)));
    }

    public function __call($name, array $arguments) {
        return $this->bind(function($value) use ($name, $arguments) {
            return $value->$name(...$arguments);
        });
    }

    public function __get($name) {
        return $this->bind(function($value) use ($name) {
            return $value->$name;
        }, $this->values);
    }

    public function value() {
        return $this->values;
    }
}
