<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminFormSectionStoreRequest;
use R64\Webforms\Http\Requests\AdminFormSectionUpdateRequest;
use R64\Webforms\Http\Resources\FormSectionResource;
use R64\Webforms\Models\FormSection;

class AdminFormSectionController
{
    public function store(AdminFormSectionStoreRequest $adminFormSectionStoreRequest)
    {
        $formSection = FormSection::makeOneOrUpdate($adminFormSectionStoreRequest->validated());

        return new FormSectionResource($formSection);
    }

    public function update(AdminFormSectionUpdateRequest $adminFormSectionUpdateRequest, FormSection $formSection)
    {
        $formSection = FormSection::makeOneOrUpdate($adminFormSectionUpdateRequest->validated(), $formSection);

        return new FormSectionResource($formSection);
    }

    public function destroy(FormSection $formSection)
    {
        $formSection->deleteMe();

        return new FormSectionResource($formSection);
    }
}
