<?php namespace Duckson\Monads;

class Identity extends BaseMonad implements Monad
{
    public function bind(callable $fn, ...$args)
    {
        return new Identity($fn($this->value, ...$args));
    }
}
