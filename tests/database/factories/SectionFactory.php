<?php

use \Faker\Generator;
use R64\Webforms\Models\Section;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Section::class, function (Generator $faker) {
    return [
        'name' => $faker->sentence,
        'slug' => $faker->slug,
    ];
});
