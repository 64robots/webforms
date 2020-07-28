<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    public $guarded = [];

    # Relations

    public function formSteps()
    {
        return $this->hasMany(FormStep::class);
    }

    # Getters

    public function getMenuTitleAttribute($value)
    {
        return empty($value) ? $this->title : $value;
    }

    # Steps

    public function addFormStep(FormStep $formStep)
    {
        return $this->formSteps()->save($formStep);
    }

    public function removeFormStep(FormStep $formStep)
    {
        return $this->formSteps()->find($formStep->id)->delete();
    }
}
