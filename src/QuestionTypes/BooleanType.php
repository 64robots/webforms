<?php

namespace R64\Webforms\QuestionTypes;

use R64\Webforms\Models\Question;

class BooleanType
{
    private $question;

    public function __construct(Question $question = null)
    {
        $this->question = $question;
    }

    public function getValidationRules()
    {
        return 'boolean';
    }

    public function cast($value)
    {
        if ($value === 'true') {
            $value = 1;
        }

        if ($value === 'false') {
            $value = 0;
        }

        return (bool)((int)$value);
    }

    public function castToFront($value)
    {
        return $value;
    }
}
