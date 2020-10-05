<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use R64\Webforms\Helpers\Sort;

class FormSection extends Model
{
    public $guarded = [];

    # Relations

    public function formSteps()
    {
        return $this->hasMany(FormStep::class);
    }

    public function formSectionable()
    {
        return $this->morphTo();
    }

    # Getters

    public function getMenuTitleAttribute($value)
    {
        return empty($value) ? $this->title : $value;
    }

    # CRUD

    public static function makeOneOrUpdate(array $data, FormSection $formSection = null)
    {
        if ($formSection === null) {
            $formSection = new self;
        }

        $formSection->sort = Sort::reorder($data['sort'], $formSection->getTable());
        $formSection->slug = $data['slug'];
        $formSection->menu_title = $data['menu_title'];
        $formSection->title = $data['title'];
        $formSection->description = $data['description'];

        $formSection->save();

        return $formSection;
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

    public function getIsCompletedByCurrentUserAttribute()
    {
        return ! auth()->user()
            ->formSteps()
            ->whereHas('formSection', function ($query) {
                $query->where('id', $this->id);
            })
            ->wherePivot('completed', false)
            ->exists();
    }
}
