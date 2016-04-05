# Monads

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is my implementation of some common monads in PHP.
It was mostly inspired by [Tom Stuart's talk](https://www.youtube.com/watch?v=uTR__8RvgvM) about refactoring Ruby code
with monads.
I wrote this library primarily for educational purposes.

I've defined the following interface for my monad implementations:

```php
interface Monad {
    public function bind(callable $fn);
    public function value();
}
```

## Usage

### Optional/maybe monad

An `Optional` object wraps any PHP object or value. It will only call functions on that value when it is not `null`.

``` php
$dateObjectOrNull = new DateTime;
$maybeDate = new Optional($dateObjectOrNull); // You can wrap any PHP value in an optional

$timestamp = $maybeDate->bind(function($value) { // When the wrapped value is `null`, this closure would never be executed
    return $value->format('Y-m-d');
})->value(); // Returns the transformed value, or null if `$dateObjectOrNull` were null

var_dump($timestamp); // string(10) "2016-03-28"
```

Alternatively, you can use magic methods and properties. Note that you still have to use the `value()` function to unwrap
the monadic value at the end.

This snippet does exactly the same as the previous snippet:

``` php
$dateObjectOrNull = new DateTime;
$maybeDate = new Optional($dateObjectOrNull);

$timestamp = $maybeDate->format('Y-m-d')->value();

var_dump($timestamp); // string(10) "2016-03-28"
```

Again, the call to the `format('Y-m-d')` instance method will only be forwarded to the wrapped value when it is not `null`.

### Many monad

A `Many` object executes operations over an array of many objects. It can only be constructed with an array of values.
The transformation function that is passed `bind()` will be applied to each value. This results in an array of transformed
values, which is passed to the next monad.

```php
$dates = new Many([
    new \DateTime('yesterday'),
    new \DateTime('now'),
    new \DateTime('tomorrow'),
]);

$timestamps = $dates->bind(function($date) {
    return $date->format('Y-m-d');
})->value();

var_dump($timestamps);
// array(3) {
//   [0] =>
//   string(10) "2016-03-27"
//   [1] =>
//   string(10) "2016-03-28"
//   [2] =>
//   string(10) "2016-03-29"
// }
```

Again, we can use magic methods or properties to achieve the same result:

```php
$dates = new Many([
    new \DateTime('yesterday'),
    new \DateTime('now'),
    new \DateTime('tomorrow'),
]);

$timestamps = $dates->format('Y-m-d')->value();

var_dump($timestamps);
// The result is the same as above.
```

## Install

Via Composer

``` bash
$ composer require duckson/monads
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mathijs.bernson@gmail.com instead of using the issue tracker.

## Credits

- [Mathijs Bernson][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/Duckson/Monads.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Duckson/Monads/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Duckson/Monads.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Duckson/Monads.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Duckson/Monads.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Duckson/Monads
[link-travis]: https://travis-ci.org/Duckson/Monads
[link-scrutinizer]: https://scrutinizer-ci.com/g/Duckson/Monads/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Duckson/Monads
[link-downloads]: https://packagist.org/packages/Duckson/Monads
[link-author]: https://github.com/mbernson
[link-contributors]: ../../contributors
