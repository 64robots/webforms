<?php

use \Faker\Generator;
use R64\Webforms\Models\FormSection;
use R64\Webforms\Models\FormStep;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(FormStep::class, function (Generator $faker) {
    return [
        'form_section_id' => factory(FormSection::class),
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
    ];
});
