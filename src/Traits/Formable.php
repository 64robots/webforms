<?php

namespace R64\Webforms\Traits;

use R64\Webforms\Models\Form;

trait Formable
{
    public function form()
    {
        return $this->morphOne(Form::class, 'formable');
    }

    public function associateForm(Form $form)
    {
        return $this->form()->save($form);
    }
}
