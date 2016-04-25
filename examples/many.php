<?php

require 'vendor/autoload.php';

use Duckson\Monads\Many;
use function \Duckson\Monads\array_flatten;

class Blog {
    function __construct(array $categories) {
        $this->categories = $categories;
    }
}

class Category {
    function __construct(array $posts) {
        $this->posts = $posts;
    }

    function posts() {
        return $this->posts;
    }
}

class Post {
    function __construct(array $comments) {
        $this->comments = $comments;
    }

    function comments() {
        return $this->comments;
    }
}

$blogs = [
    new Blog([
        new Category([
            new Post(["I love cats", "I love dogs"]),
            new Post(["I love mice", "I love pigs"]),
        ]),
        new Category([
            new Post(["I hate cats", "I hate dogs"]),
            new Post(["I hate mice", "I hate pigs"]),
        ]),
    ]),
    new Blog([
        new Category([
            new Post(["Red is better than blue"]),
        ]),
        new Category([
            new Post(["Blue is better than red"]),
        ]),
    ])
];

function words_in(array $blogs) {
    $comments = (new Many($blogs))
        ->categories
        ->posts()
        ->comments()
        ->value();

    $words = array_map(function($value) {
        return explode(' ', $value);
    }, $comments);

    return array_flatten($words);
}

var_dump(words_in($blogs));
