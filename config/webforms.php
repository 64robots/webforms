<?php

use R64\Webforms\Models\QuestionTypes;

return [
    'date_format' => 'Y-m-d',
    'year_month_format' => 'Y-m',
    'phone' => [
        'min_length' => 13,
    ],
    'age' => [
        'min' => 18,
        'max' => 99,
    ],
    'percent' => [
        'min' => 0,
        'max' => 100,
    ],
    'answers_channel' => 'answers_channel',
    'fields_to_be_confirmed' => [
        QuestionTypes::EMAIL_TYPE,
        QuestionTypes::PHONE_TYPE,
    ],
    'user_model' => 'App\User',
];
