<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\SectionCollection;
use R64\Webforms\Models\Section;

class SectionController
{
    public function index()
    {
        return new SectionCollection(Section::orderBy('sort')->get());
    }
}
