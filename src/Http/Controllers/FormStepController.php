<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Resources\FormStepCollection;
use R64\Webforms\Http\Resources\FormStepResource;
use R64\Webforms\Models\FormStep;

class FormStepController
{
    public function index()
    {
        return new FormStepCollection(
            auth()->user()
                ->formSteps()
                ->with('formSection')
                ->orderBy('sort')
                ->get()
        );
    }

    public function update(FormStep $formStep)
    {
        $formStep->markFictionalAnswersAsRealFor(auth()->user());

        return new FormStepResource($formStep->load('formSection'));
    }
}
