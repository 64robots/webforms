<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminFormStepStoreRequest;
use R64\Webforms\Http\Requests\AdminFormStepUpdateRequest;
use R64\Webforms\Http\Resources\FormStepResource;
use R64\Webforms\Models\FormStep;

class AdminFormStepController
{
    public function store(AdminFormStepStoreRequest $adminFormStepStoreRequest)
    {
        $formStep = FormStep::makeOneOrUpdate($adminFormStepStoreRequest->validated());

        return new FormStepResource($formStep);
    }

    public function update(AdminFormStepUpdateRequest $adminFormStepUpdateRequest, FormStep $formStep)
    {
        $formStep = FormStep::makeOneOrUpdate($adminFormStepUpdateRequest->validated(), $formStep);

        return new FormStepResource($formStep);
    }

    public function destroy(FormStep $formStep)
    {
        $formStep->deleteMe();

        return new FormStepResource($formStep->load('form'));
    }
}
