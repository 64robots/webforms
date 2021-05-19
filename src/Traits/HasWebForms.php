<?php

namespace R64\Webforms\Traits;

use R64\Webforms\Models\Answer;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\FormStepUser;
use R64\Webforms\Models\Question;

trait HasWebForms
{
    # Form Steps

    public function formSteps()
    {
        return $this->belongsToMany(FormStep::class)
            ->using(FormStepUser::class)
            ->withPivot('completed')
            ->withTimestamps();
    }

    public function addFormSteps($formSteps = null)
    {
        if ($formSteps) {
            if (is_array($formSteps)) {
                foreach($formSteps as $formStep) {
                    $this->addFormStep($formStep);
                }
                return;
            } else {
                return $this->addFormStep($formSteps);
            }
        }

        $this->addDefaultAnswers();
        FormStep::all()->each(function ($formStep) {
            $this->formSteps()->syncWithoutDetaching($formStep);
        });
    }

    public function addFormStep($formStep = null)
    {
        $this->addDefaultAnswers();

        return $this->formSteps()->syncWithoutDetaching($formStep);
    }

    public function markFormStepAsUncompleted($formStep)
    {
        if ($this->hasFormStep($formStep)) {
            $this->formSteps()->updateExistingPivot($formStep->id, [
                'completed' => false,
            ]);
        }
    }

    public function markFormStepAsCompleted($formStep)
    {
        if ($this->hasFormStep($formStep)) {
            $this->formSteps()->updateExistingPivot($formStep->id, [
                'completed' => true,
            ]);
        }
    }

    private function hasFormStep($formStep): bool
    {
        return ! ! $this->formSteps()->find($formStep);
    }

    # Answers

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function addDefaultAnswers()
    {
        Question::whereNotNull('default_value')
            ->get()
            ->each(function ($question) {
                if ($this->hasAnswerForThisQuestion($question)) {
                    return;
                }

                Answer::makeOneFictional($this, $question);
            });
    }

    private function hasAnswerForThisQuestion($question): bool
    {
        return $this->answers()->where('question_id', $question->id)->exists();
    }
}
