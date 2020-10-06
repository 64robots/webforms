<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Webforms\Factories\FormSectionFactory;
use R64\Webforms\Helpers\Slug;
use R64\Webforms\Helpers\Sort;

class FormSection extends Model
{
    use SoftDeletes;

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

    public function getMenuTitleFrontendAttribute()
    {
        return empty($this->menu_title) ? $this->title : $this->menu_title;
    }

    # CRUD

    public static function build(string $title)
    {
        return FormSectionFactory::build($title);
    }

    public static function updateFormSection(FormSection $formSection)
    {
        return FormSectionFactory::update($formSection);
    }

    public static function makeOneOrUpdate(array $data, FormSection $formSection = null)
    {
        if ($formSection === null) {
            $formSection = new self;
        }

        $formSection->sort = Sort::reorder($data['sort'], $formSection->getTable(), 'sort', $formSection->sort);
        $formSection->slug = $data['slug'];
        $formSection->menu_title = $data['menu_title'];
        $formSection->title = $data['title'];
        $formSection->description = $data['description'];

        $formSection->save();

        return $formSection;
    }

    public function deleteMe()
    {
        $this->delete();
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

    # Helpers

    public static function getLastSort()
    {
        $lastSort = self::max('sort') ?? 0;

        return ((int)$lastSort) + 1;
    }

    public static function getSlugFromTitle($title)
    {
        return Slug::make($title, (new self)->getTable());
    }
}
