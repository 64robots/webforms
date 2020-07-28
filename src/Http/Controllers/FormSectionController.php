<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\FormSectionCollection;
use R64\Webforms\Models\FormSection;

class FormSectionController
{
    public function index()
    {
        return new FormSectionCollection(FormSection::orderBy('sort')->get());
    }
}
