<?php

use \Faker\Generator;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(config('webforms.user_model'), function (Generator $faker) {
    static $password;

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
    ];
});
