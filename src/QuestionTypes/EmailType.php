<?php

namespace R64\Webforms\QuestionTypes;

use R64\Webforms\Models\Question;

class EmailType
{
    public const TYPE = 'email';

    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'email';
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
