<?php

use R64\Webforms\Models\QuestionTypes;

return [
    'date_format' => 'Y-m-d',
    'year_month_format' => 'Y-m',
    'fields_to_be_confirmed' => [
        QuestionTypes::EMAIL_TYPE,
        QuestionTypes::PHONE_TYPE,
    ],
    'user_model' => 'App\User',
];
