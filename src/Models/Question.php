<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use R64\Webforms\Factories\QuestionFactory;
use R64\Webforms\Helpers\Slug;
use R64\Webforms\Helpers\Sort;
use R64\Webforms\QuestionTypes\TextType;

class Question extends Model
{
    use SoftDeletes;

    public $guarded = [];

    protected $casts = [
        'sort' => 'integer',
        'required' => 'boolean',
        'depends_on' => 'integer',
        'shown_when' => 'array',
        'options' => 'array',
    ];

    # Relations

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function currentRealConfirmedUserAnswers()
    {
        return $this->answers()->byCurrentUser()->confirmed()->real();
    }

    public function currentUserAnswers()
    {
        return $this->answers()->byCurrentUser();
    }

    public function dependsOn()
    {
        return $this->belongsTo(Question::class, 'depends_on');
    }

    public function formStep()
    {
        return $this->belongsTo(FormStep::class);
    }

    # Getters

    public function getCurrentUserAnswerAttribute()
    {
        return $this->currentUserAnswers()->current()->first();
    }

    # CRUD

    public static function build(FormStep $formStep, string $title)
    {
        return QuestionFactory::build($formStep, $title);
    }

    public static function updateQuestion(Question $question)
    {
        return QuestionFactory::update($question);
    }

    public static function makeOneOrUpdate(array $data, Question $question = null)
    {
        if ($question === null) {
            $question = new self;
        }

        /** @var FormStep $formStep */
        $formStep = FormStep::findOrFail($data['form_step_id']);
        $question->sort = Sort::reorderCollection($formStep->questions, $data['sort'], 'sort', $question->sort);
        $question->formStep()->associate($formStep);
        $question->depends_on = $data['depends_on'];
        $question->slug = $data['slug'];
        $question->group_by = $data['group_by'];
        $question->group_by_description = $data['group_by_description'];
        $question->label_position = $data['label_position'];
        $question->help_title = $data['help_title'];
        $question->help_body = $data['help_body'];
        $question->type = $data['type'];
        $question->post_input_text = $data['post_input_text'];
        $question->title = $data['title'];
        $question->description = $data['description'];
        $question->error_message = $data['error_message'];
        $question->default_value = $data['default_value'];
        $question->min = $data['min'];
        $question->max = $data['max'];
        $question->shown_when = $data['shown_when'];
        $question->options = $data['options'];
        $question->required = $data['required'];

        $question->save();

        return $question;
    }

    public function deleteMe()
    {
        $this->delete();
    }

    # Helpers

    public function cast($value)
    {
        return $this->getQuestionType()->cast($value);
    }

    public function castToFront($value)
    {
        return $this->getQuestionType()->castToFront($value);
    }

    public function getValidationRules()
    {
        return $this->getQuestionType()->getValidationRules();
    }

    public function getQuestionType()
    {
        $type = Str::studly($this->type);
        $class = "R64\Webforms\QuestionTypes\\" . $type . "Type";

        return new $class($this);
    }

    public function castNullValueToFront($value)
    {
        if (is_null($value)) {
            return null;
        }

        return $this->castToFront($this->cast($value));
    }

    public static function getLastSort($formStep)
    {
        if (is_numeric($formStep)) {
            $formStep = FormStep::findOrFail($formStep);
        }

        $lastSort = $formStep->questions()->max('sort') ?? 0;

        return ((int)$lastSort) + 1;
    }

    public static function getSlugFromTitle($title)
    {
        return Slug::make($title, (new self)->getTable());
    }

    public static function getDefaultType()
    {
        return TextType::TYPE;
    }

    public static function getDefaultLabelPosition()
    {
        return 'top';
    }

    public static function getDefaultRequired()
    {
        return 0;
    }
}
