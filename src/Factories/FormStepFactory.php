<?php

namespace R64\Webforms\Factories;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;

class FormStepFactory
{
    public $form;
    public $title;
    public $sort;
    public $slug;
    public $menuTitle;
    public $description;
    public $isPersonalData;
    public $formStep;

    public static function build(Form $form, string $title)
    {
        $factory = new self;

        $factory->form($form);
        $factory->title($title);

        return $factory;
    }

    public static function update(FormStep $formStep)
    {
        $factory = new self;

        $factory->formStep = $formStep;

        $factory->form($formStep->form)
            ->sort($formStep->sort)
            ->slug($formStep->slug)
            ->menuTitle($formStep->menu_title)
            ->title($formStep->title)
            ->description($formStep->description)
            ->isPersonalData($formStep->is_personal_data);

        return $factory;
    }

    public function save()
    {
        $data = [
            'form_id' => $this->getForm()->id,
            'sort' => $this->getSort(),
            'slug' => $this->getSlug(),
            'menu_title' => $this->getMenuTitle(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'is_personal_data' => $this->getIsPersonalData(),
        ];

        return FormStep::makeOneOrUpdate($data, $this->formStep);
    }

    public function form(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function sort(int $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    private function getSort()
    {
        return $this->sort ? $this->sort : FormStep::getLastSort($this->form);
    }

    public function slug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    private function getSlug()
    {
        return $this->slug ?? FormStep::getSlugFromTitle($this->title);
    }

    public function menuTitle(string $menuTitle = null)
    {
        $this->menuTitle = $menuTitle;

        return $this;
    }

    private function getMenuTitle()
    {
        return $this->menuTitle;
    }

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    private function getTitle()
    {
        return $this->title;
    }

    public function description(string $description = null)
    {
        $this->description = $description;

        return $this;
    }

    private function getDescription()
    {
        return $this->description;
    }

    public function isPersonalData(int $isPersonalData = 0)
    {
        $this->isPersonalData = $isPersonalData;

        return $this;
    }

    private function getIsPersonalData()
    {
        return $this->isPersonalData ?? FormStep::getDefaultIsPersonalData();
    }
}
