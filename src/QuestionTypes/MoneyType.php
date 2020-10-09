<?php

namespace R64\Webforms\QuestionTypes;

use R64\Webforms\Models\Question;

class MoneyType
{
    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'numeric';
    }

    public function cast($value)
    {
        return (int)$value;
    }

    public function castToFront($value)
    {
        return $value;
    }
}
