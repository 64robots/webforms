<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\QuestionCollection;
use R64\Webforms\Models\Question;

class QuestionController
{
    public function index()
    {
        return new QuestionCollection(
            Question::with('formStep')
                ->when(request('form_step'), function ($query) {
                    return $query->where('form_step_id', request('form_step'));
                })
                ->orderBy('sort')
                ->get()
        );
    }
}
