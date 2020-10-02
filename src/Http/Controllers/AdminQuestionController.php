<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminQuestionStoreRequest;
use R64\Webforms\Http\Resources\QuestionResource;
use R64\Webforms\Models\Question;

class AdminQuestionController
{
    public function store(AdminQuestionStoreRequest $adminQuestionStoreRequest)
    {
        $question = Question::makeOne($adminQuestionStoreRequest->validated());

        return new QuestionResource($question);
    }
}
