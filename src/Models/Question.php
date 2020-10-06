<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use R64\Webforms\Helpers\Sort;

class Question extends Model
{
    use SoftDeletes;

    public $guarded = [];

    protected $casts = [
        'sort' => 'integer',
        'required' => 'boolean',
        'depends_on' => 'integer',
        'showed_when' => 'array',
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

    public function formStep()
    {
        return $this->belongsTo(FormStep::class);
    }

    # Getters

    public function getCurrentUserAnswerAttribute()
    {
        return $this->currentUserAnswers()->current()->first();
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
        $question->showed_when = $data['showed_when'];
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
        if (in_array($this->type, [QuestionTypes::DATE_TYPE, QuestionTypes::YEAR_MONTH_TYPE])) {
            $value = Carbon::parse($value);
        }

        if (in_array($this->type, [QuestionTypes::INTEGER_TYPE, QuestionTypes::MONEY_TYPE, QuestionTypes::PERCENT_TYPE, QuestionTypes::AGE_TYPE])) {
            $value = (int)$value;
        }

        if ($this->type === QuestionTypes::BOOLEAN_TYPE) {
            if ($value === 'true') {
                $value = 1;
            }
            if ($value === 'false') {
                $value = 0;
            }
            $value = (bool)((int)$value);
        }

        return $value;
    }

    public function castToFront($value)
    {
        if ($this->type === QuestionTypes::DATE_TYPE) {
            return $value->toDateString();
        }

        if ($this->type === QuestionTypes::YEAR_MONTH_TYPE) {
            return $value->format('Y-m');
        }

        return $value;
    }

    public function getValidationRules()
    {
        $rule = 'string';

        if ($this->type === QuestionTypes::DATE_TYPE) {
            $rule = 'date:' . config('webforms.date_format');
        }

        if ($this->type === QuestionTypes::YEAR_MONTH_TYPE) {
            $rule = 'date:' . config('webforms.year_month_format');
        }

        if (in_array($this->type, [QuestionTypes::INTEGER_TYPE, QuestionTypes::MONEY_TYPE])) {
            $rule = 'numeric';
        }

        if (in_array($this->type, [QuestionTypes::PERCENT_TYPE, QuestionTypes::AGE_TYPE])) {
            $rule = 'numeric|between:' . $this->min . ',' . $this->max;
        }

        if ($this->type === QuestionTypes::OPTIONS_TYPE) {
            $rule = 'in:' . implode(",", array_keys($this->options));
        }

        if ($this->type === QuestionTypes::BOOLEAN_TYPE) {
            $rule = 'boolean';
        }

        if ($this->type === QuestionTypes::EMAIL_TYPE) {
            $rule = 'email';
        }

        if ($this->type === QuestionTypes::PHONE_TYPE) {
            $rule = 'string|min:' . $this->min;
        }

        return $rule;
    }

    public function castNullValueToFront($value)
    {
        if (is_null($value)) {
            return null;
        }

        return $this->castToFront($this->cast($value));
    }
}
