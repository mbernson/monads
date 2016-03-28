<?php namespace Duckson\Monads;

class Optional implements Monad {
    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function bind(callable $fn) {
        if(is_null($this->value)) {
            return new Optional(null);
        } else {
            return new Optional($fn($this->value));
        }
    }

    public function __call($name, array $arguments) {
        return $this->bind(function($value) use ($name, $arguments) {
            return call_user_func_array([$value, $name], $arguments);
        });
    }

    public function __get($name) {
        return $this->bind(function($value) use ($name) {
            return $value->$name;
        });
    }

    public function value() {
        return $this->value;
    }
}
