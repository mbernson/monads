<?php namespace Duckson\Monads;

interface Monad
{
    /**
     * Bind an operation to the monad object that transforms its value.
     * The `bind()` function will unwrap the monadic value, and feeds it to the transformer function.
     * `bind()` will then create and return a new monadic value that can be passed on,
     * until the user finally unwraps it using the `value()` function.
     *
     * The bind function MUST NOT modify the monadic value in-place. It always returns a new monadic value.
     *
     * @param callable $fn
     * @return static
     */
    public function bind(callable $fn, ...$args);

    /**
     * Returns the unwrapped value from the monad.
     *
     * @return mixed
     */
    public function value();
}
