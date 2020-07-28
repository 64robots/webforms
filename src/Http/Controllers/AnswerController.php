<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\StoreAnswerRequest;
use R64\Webforms\Http\Resources\QuestionResource;
use R64\Webforms\Models\Answer;

class AnswerController
{
    public function store(StoreAnswerRequest $request)
    {
        Answer::makeOneOrUpdate($request->validated(), $request->answer);

        return new QuestionResource($request->question->load('formStep'));
    }
}
