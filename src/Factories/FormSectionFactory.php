<?php

namespace R64\Webforms\Factories;

use R64\Webforms\Models\FormSection;

class FormSectionFactory
{
    public $sort;
    public $slug;
    public $menuTitle;
    public $title;
    public $description;
    public $formSection;

    public static function build(string $title)
    {
        $factory = new self;

        $factory->title($title);

        return $factory;
    }

    public static function update(FormSection $formSection)
    {
        $factory = new self;

        $factory->formSection = $formSection;

        $factory->sort($formSection->sort)
            ->slug($formSection->slug)
            ->menuTitle($formSection->menu_title)
            ->title($formSection->title)
            ->description($formSection->description);

        return $factory;
    }

    public function save()
    {
        $data = [
            'sort' => $this->getSort(),
            'slug' => $this->getSlug(),
            'menu_title' => $this->getMenuTitle(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ];

        return FormSection::makeOneOrUpdate($data, $this->formSection);
    }

    public function sort(int $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    private function getSort()
    {
        return $this->sort ? $this->sort : FormSection::getLastSort();
    }

    public function slug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    private function getSlug()
    {
        return $this->slug ?? FormSection::getSlugFromTitle($this->title);
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
}
