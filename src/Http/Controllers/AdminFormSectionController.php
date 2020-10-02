<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminFormSectionStoreRequest;
use R64\Webforms\Http\Resources\FormSectionResource;
use R64\Webforms\Models\FormSection;

class AdminFormSectionController
{
    public function store(AdminFormSectionStoreRequest $adminFormSectionStoreRequest)
    {
        $formSection = FormSection::makeOne($adminFormSectionStoreRequest->validated());

        return new FormSectionResource($formSection);
    }
}
