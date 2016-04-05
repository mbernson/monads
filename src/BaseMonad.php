<?php namespace Duckson\Monads;

abstract class BaseMonad implements Monad
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    abstract public function bind(callable $fn, ...$args);

    /**
     * Unwraps the value of the monad and returns it.
     *
     * @return mixed
     */
    public function value()
    {
        if ($this->value instanceof Monad) {
            return $this->value->value();
        } else {
            return $this->value;
        }
    }

    /**
     * Forward method calls to the wrapped value.
     * The following statements are equivalent:
     *
     *     $monad->foo('bar', 'baz') ==== $monad->bind(function($value) {
     *         return $value->foo('bar', 'baz');
     *     })
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return $this->bind(function ($value) use ($name, $arguments) {
            return $value->$name(...$arguments);
        });
    }

    /**
     * Forward accessors to the wrapped value.
     * The following statements are equivalent:
     *
     *     $monad->foo ==== $monad->bind(function($value) {
     *         return $value->foo;
     *     });
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->bind(function ($value) use ($name) {
            return $value->$name;
        }, $this->value);
    }
}
