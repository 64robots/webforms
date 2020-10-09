<?php

namespace R64\Webforms\QuestionTypes;

use R64\Webforms\Models\Question;

class TextType
{
    public const TYPE = 'text';

    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'string';
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
