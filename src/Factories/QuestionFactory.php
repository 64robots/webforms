<?php

namespace R64\Webforms\Factories;

use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;

class QuestionFactory
{
    public $formStep;
    public $sort;
    public $dependsOn;
    public $groupBy;
    public $groupByDescription;
    public $labelPosition;
    public $helpTitle;
    public $helpBody;
    public $type;
    public $postInputText;
    public $errorMessage;
    public $defaultValue;
    public $min;
    public $max;
    public $showedWhen;
    public $options;
    public $required;
    public $title;
    public $slug;
    public $description;
    public $question;

    public static function build(FormStep $formStep, string $title)
    {
        $factory = new self;

        $factory->formStep($formStep);
        $factory->title($title);

        return $factory;
    }

    public static function update(Question $question)
    {
        $factory = new self;

        $factory->question = $question;

        $factory->formStep($question->formStep)
            ->sort($question->sort)
            ->slug($question->slug)
            ->title($question->title)
            ->type($question->type)
            ->groupBy($question->group_by)
            ->groupByDescription($question->group_by_description)
            ->labelPosition($question->label_position)
            ->helpTitle($question->help_title)
            ->helpBody($question->help_body)
            ->postInputText($question->post_input_text)
            ->errorMessage($question->error_message)
            ->defaultValue($question->default_value)
            ->description($question->description)
            ->dependsOn($question->dependsOn)
            ->min($question->min)
            ->max($question->max)
            ->showedWhen($question->showed_when)
            ->options($question->options)
            ->required($question->required);

        return $factory;
    }

    public function save()
    {
        $data = [
            'form_step_id' => $this->getFormStep()->id,
            'sort' => $this->getSort(),
            'slug' => $this->getSlug(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'depends_on' => $this->getDependsOn()->id,
            'type' => $this->getType(),
            'group_by' => $this->getGroupBy(),
            'group_by_description' => $this->getGroupByDescription(),
            'label_position' => $this->getLabelPosition(),
            'help_title' => $this->getHelpTitle(),
            'help_body' => $this->getHelpBody(),
            'post_input_text' => $this->getPostInputText(),
            'error_message' => $this->getErrorMessage(),
            'default_value' => $this->getDefaultValue(),
            'min' => $this->getMin(),
            'max' => $this->getMax(),
            'showed_when' => $this->getShowedWhen(),
            'options' => $this->getOptions(),
            'required' => $this->getRequired(),
        ];

        return Question::makeOneOrUpdate($data, $this->question);
    }

    public function formStep(FormStep $formStep)
    {
        $this->formStep = $formStep;

        return $this;
    }

    public function getFormStep()
    {
        return $this->formStep;
    }

    public function sort(int $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    private function getSort()
    {
        return $this->sort ? $this->sort : Question::getLastSort($this->formStep);
    }

    public function slug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    private function getSlug()
    {
        return $this->slug ?? Question::getSlugFromTitle($this->title);
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

    public function dependsOn(Question $question = null)
    {
        $this->dependsOn = $question;

        return $this;
    }

    private function getDependsOn()
    {
        return $this->dependsOn ? $this->dependsOn : (object)['id' => null];
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    private function getType()
    {
        return $this->type ?? Question::getDefaultType();
    }

    public function groupBy(string $groupBy = null)
    {
        $this->groupBy = $groupBy;

        return $this;
    }

    private function getGroupBy()
    {
        return $this->groupBy;
    }

    public function groupByDescription(string $groupByDescription = null)
    {
        $this->groupByDescription = $groupByDescription;

        return $this;
    }

    private function getGroupByDescription()
    {
        return $this->groupByDescription;
    }

    public function labelPosition(string $labelPosition)
    {
        $this->labelPosition = $labelPosition;

        return $this;
    }

    private function getLabelPosition()
    {
        return $this->labelPosition ?? Question::getDefaultLabelPosition();
    }

    public function helpTitle(string $helpTitle = null)
    {
        $this->helpTitle = $helpTitle;

        return $this;
    }

    private function getHelpTitle()
    {
        return $this->helpTitle;
    }

    public function helpBody(string $helpBody = null)
    {
        $this->helpBody = $helpBody;

        return $this;
    }

    private function getHelpBody()
    {
        return $this->helpBody;
    }

    public function postInputText(string $postInputText = null)
    {
        $this->postInputText = $postInputText;

        return $this;
    }

    private function getPostInputText()
    {
        return $this->postInputText;
    }

    public function errorMessage(string $errorMessage = null)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    private function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function defaultValue(string $defaultValue = null)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    private function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function min(string $min = null)
    {
        $this->min = $min;

        return $this;
    }

    private function getMin()
    {
        return $this->min;
    }

    public function max(string $max = null)
    {
        $this->max = $max;

        return $this;
    }

    private function getMax()
    {
        return $this->max;
    }

    public function showedWhen(array $showedWhen = null)
    {
        $this->showedWhen = $showedWhen;

        return $this;
    }

    private function getShowedWhen()
    {
        return $this->showedWhen;
    }

    public function options(array $options = null)
    {
        $this->options = $options;

        return $this;
    }

    private function getOptions()
    {
        return $this->options;
    }

    public function required(int $required = 0)
    {
        $this->required = $required;

        return $this;
    }

    private function getRequired()
    {
        return $this->required ?? Question::getDefaultRequired();
    }
}
