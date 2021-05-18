<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\FormCollection;
use R64\Webforms\Models\Form;

class FormController
{
    public function index()
    {
        return new FormCollection(Form::orderBy('sort')->get());
    }
}
