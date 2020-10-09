<?php

namespace R64\Webforms\QuestionTypes;

use Illuminate\Support\Carbon;
use R64\Webforms\Models\Question;

class DateType
{
    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'date:' . config('webforms.date_format');
    }

    public function cast($value)
    {
        return Carbon::parse($value);
    }

    public function castToFront($value)
    {
        return $value->toDateString();
    }
}
