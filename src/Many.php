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

class Many extends BaseMonad implements Monad {
    public function bind(callable $fn, ...$args) {
        return new Many(array_flatten(array_map($fn, $this->value)));
    }
}
