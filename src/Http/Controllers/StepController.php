<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\StepCollection;
use R64\Webforms\Models\Step;

class StepController
{
    public function index()
    {
        return new StepCollection(Step::orderBy('sort')->get()->load('section'));
    }
}
