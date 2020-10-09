<?php

namespace R64\Webforms\QuestionTypes;

use R64\Webforms\Models\Question;

class PhoneType
{
    public const TYPE = 'phone';

    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'string|min:' . $this->question->min;
    }

    public function cast($value)
    {
        return $value;
    }

    public function castToFront($value)
    {
        return $value;
    }
}
