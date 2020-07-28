<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Question extends Model
{
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

        if ($this->type === QuestionTypes::PERCENT_TYPE) {
            $rule = 'numeric|between:' . config('webforms.percent.min') . ',' . config('webforms.percent.max');
        }

        if ($this->type === QuestionTypes::AGE_TYPE) {
            $rule = 'numeric|between:' . config('webforms.age.min') . ',' . config('webforms.age.max');
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
            $rule = 'string|min:' . config('webforms.phone.min_length');
        }

        return $rule;
    }

    public function getDefaultValueToFrontAttribute()
    {
        if (is_null($this->default_value)) {
            return null;
        }

        return $this->castToFront($this->cast($this->default_value));
    }

    public function isPercent(): bool
    {
        return $this->type === QuestionTypes::PERCENT_TYPE;
    }
}
