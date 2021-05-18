<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Webforms\Factories\FormFactory;
use R64\Webforms\Helpers\Slug;
use R64\Webforms\Helpers\Sort;

class Form extends Model
{
    use SoftDeletes;

    public $guarded = [];

    # Relations

    public function formSteps()
    {
        return $this->hasMany(FormStep::class);
    }

    public function formable()
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
        return FormFactory::build($title);
    }

    public static function updateForm(Form $form)
    {
        return FormFactory::update($form);
    }

    public static function makeOneOrUpdate(array $data, Form $form = null)
    {
        if ($form === null) {
            $form = new self;
        }

        $form->sort = Sort::reorder($data['sort'], $form->getTable(), 'sort', $form->sort);
        $form->slug = $data['slug'];
        $form->menu_title = $data['menu_title'];
        $form->title = $data['title'];
        $form->description = $data['description'];

        $form->save();

        return $form;
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
            ->whereHas('form', function ($query) {
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
