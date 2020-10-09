<?php

use R64\Webforms\Models\QuestionTypes;
use R64\Webforms\QuestionTypes\EmailType;
use R64\Webforms\QuestionTypes\PhoneType;

return [
    'date_format' => 'Y-m-d',
    'year_month_format' => 'Y-m',
    'fields_to_be_confirmed' => [
        EmailType::TYPE,
        PhoneType::TYPE,
    ],
    'user_model' => 'App\User',
];
