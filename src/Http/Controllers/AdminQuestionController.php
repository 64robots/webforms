<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminQuestionStoreRequest;
use R64\Webforms\Http\Requests\AdminQuestionUpdateRequest;
use R64\Webforms\Http\Resources\QuestionResource;
use R64\Webforms\Models\Question;

class AdminQuestionController
{
    public function store(AdminQuestionStoreRequest $adminQuestionStoreRequest)
    {
        $question = Question::makeOneOrUpdate($adminQuestionStoreRequest->validated());

        return new QuestionResource($question);
    }

    public function update(AdminQuestionUpdateRequest $adminQuestionUpdateRequest, Question $question)
    {
        $question = Question::makeOneOrUpdate($adminQuestionUpdateRequest->validated(), $question);

        return new QuestionResource($question);
    }

    public function destroy(Question $question)
    {
        $question->deleteMe();

        return new QuestionResource($question->load('formStep'));
    }
}
