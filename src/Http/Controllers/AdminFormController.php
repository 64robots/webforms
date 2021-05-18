<?php

namespace R64\Webforms\Http\Controllers;

use R64\Webforms\Http\Requests\AdminFormStoreRequest;
use R64\Webforms\Http\Requests\AdminFormUpdateRequest;
use R64\Webforms\Http\Resources\FormResource;
use R64\Webforms\Models\Form;

class AdminFormController
{
    public function store(AdminFormStoreRequest $adminFormStoreRequest)
    {
        $form = Form::makeOneOrUpdate($adminFormStoreRequest->validated());

        return new FormResource($form);
    }

    public function update(AdminFormUpdateRequest $adminFormUpdateRequest, Form $form)
    {
        $form = Form::makeOneOrUpdate($adminFormUpdateRequest->validated(), $form);

        return new FormResource($form);
    }

    public function destroy(Form $form)
    {
        $form->deleteMe();

        return new FormResource($form);
    }
}
