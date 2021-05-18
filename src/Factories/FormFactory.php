<?php

namespace R64\Webforms\Factories;

use R64\Webforms\Models\Form;

class FormFactory
{
    public $sort;
    public $slug;
    public $menuTitle;
    public $title;
    public $description;
    public $form;

    public static function build(string $title)
    {
        $factory = new self;

        $factory->title($title);

        return $factory;
    }

    public static function update(Form $form)
    {
        $factory = new self;

        $factory->form = $form;

        $factory->sort($form->sort)
            ->slug($form->slug)
            ->menuTitle($form->menu_title)
            ->title($form->title)
            ->description($form->description);

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

        return Form::makeOneOrUpdate($data, $this->form);
    }

    public function sort(int $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    private function getSort()
    {
        return $this->sort ? $this->sort : Form::getLastSort();
    }

    public function slug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    private function getSlug()
    {
        return $this->slug ?? Form::getSlugFromTitle($this->title);
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
