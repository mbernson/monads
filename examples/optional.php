<?php

require 'vendor/autoload.php';

use Duckson\Monads\Optional;

class Person {
    public function __construct() {
        $this->date_of_birth = new DateTime;
    }
}

date_default_timezone_set('Europe/Amsterdam');

$maybePerson = new Optional(new Person);
$date = $maybePerson->date_of_birth->format('H:i')->value();
var_dump($date); // Outputs the time

$maybePerson = new Optional(null);
$date = $maybePerson->date_of_birth->format('H:i')->value();
var_dump($date); // Outputs null
