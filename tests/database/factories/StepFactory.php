<?php

use \Faker\Generator;
use R64\Webforms\Models\Section;
use R64\Webforms\Models\Step;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Step::class, function (Generator $faker) {
    return [
        'section_id' => factory(Section::class),
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
    ];
});
