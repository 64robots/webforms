<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\StoreFormSectionRequest;
use R64\Webforms\Http\Resources\FormSectionCollection;
use R64\Webforms\Http\Resources\FormSectionResource;
use R64\Webforms\Models\FormSection;

class FormSectionController
{
    public function index()
    {
        return new FormSectionCollection(FormSection::orderBy('sort')->get());
    }

    public function store(StoreFormSectionRequest $formSectionStoreRequest)
    {
        $formSection = FormSection::makeOne($formSectionStoreRequest->validated());

        return new FormSectionResource($formSection);
    }
}
