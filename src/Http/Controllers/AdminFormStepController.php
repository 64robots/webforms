<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminFormStepStoreRequest;
use R64\Webforms\Http\Resources\FormStepResource;
use R64\Webforms\Models\FormStep;

class AdminFormStepController
{
    public function store(AdminFormStepStoreRequest $adminFormStepStoreRequest)
    {
        $formStep = FormStep::makeOne($adminFormStepStoreRequest->validated());

        return new FormStepResource($formStep);
    }
}
