<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\QuestionCollection;
use R64\Webforms\Models\Question;

class QuestionController
{
    public function index()
    {
        return new QuestionCollection(
            Question::with('step')
                ->when(request('step'), function ($query) {
                    return $query->where('step_id', request('step'));
                })
                ->orderBy('sort')
                ->get()
        );
    }
}
