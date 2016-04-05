<?php namespace Duckson\Monads;

class Optional extends BaseMonad implements Monad {
    public function bind(callable $fn, ...$args) {
        if(is_null($this->value)) {
            return new Optional(null);
        } else {
            return new Optional($fn($this->value));
        }
    }
}
