<?php namespace Duckson\Monads;

class Many extends BaseMonad implements Monad
{
    public function __construct($value) {
        if(is_array($value)) {
            parent::__construct($value);
        } else {
            parent::__construct([$value]);
        }
    }

    public function bind(callable $fn, ...$args)
    {
        return new Many(array_flatten(array_map($fn, $this->value)));
    }
}

function array_flatten($arg)
{
    if (is_array($arg)) {
        return array_reduce($arg, function ($collector, $element) {
            return array_merge($collector, array_flatten($element));
        }, []);
    } else {
        return [$arg];
    }
}
