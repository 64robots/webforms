<?php

namespace R64\Webforms\QuestionTypes;

use R64\Webforms\Models\Question;

class OptionsType
{
    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'in:' . implode(",", array_keys($this->question->options));
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
