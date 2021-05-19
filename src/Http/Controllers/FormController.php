<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\FormCollection;
use R64\Webforms\Models\Form;

class FormController
{
    public function index()
    {
        return new FormCollection(auth()->user()->formSteps()->with('form')->get()->pluck('form')->unique()->sortBy('sort')->values());
    }
}
