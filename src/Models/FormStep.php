<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;

class FormStep extends Model
{
    public $guarded = [];

    # Relations

    public function formSection()
    {
        return $this->belongsTo(FormSection::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function questionsWithoutCurrentUserAnswer()
    {
        return $this->questions()
            ->whereDoesntHave('currentRealConfirmedUserAnswers')
            ->where('questions.required', true);
    }

    public function users()
    {
        return $this->belongsToMany(config('webforms.user_model'))
            ->using(FormStepUser::class)
            ->withPivot('completed')
            ->withTimestamps();
    }

    # Getters

    public function getMenuTitleAttribute($value)
    {
        return empty($value) ? $this->title : $value;
    }

    public function getIsCompletedByCurrentUserAttribute()
    {
        $userFormStep = $this->users()->find(auth()->user()->id);

        if (is_null($userFormStep)) {
            return null;
        }

        return $userFormStep->pivot->completed;
    }

    # Section

    public function associateFormSection(FormSection $formSection)
    {
        return $this->formSection()->associate($formSection);
    }

    # Question

    public function getFictionalAnswersFor($user)
    {
        return $user->answers()
            ->whereHas('question', function ($query) {
                return $query->where('form_step_id', $this->id);
            })
            ->fictional()
            ->get();
    }

    public function markFictionalAnswersAsRealFor($user)
    {
        $fictionalAnswers = $this->getFictionalAnswersFor($user);

        $fictionalAnswers->each->markAsReal();

        $user->markFormStepAsCompleted($this);
    }

    public function uncompletedSonQuestions()
    {
        $motherQuestionsIds = $this->questions()->whereNotNull('depends_on')->pluck('depends_on')->unique();

        return Question::findMany($motherQuestionsIds)->first(function ($motherQuestion) {
            $motherQuestionAnswer = $motherQuestion->current_user_answer;

            if ((! $motherQuestionAnswer) || $motherQuestionAnswer->is_real === false) {
                return false;
            }

            $sonQuestionsShowed = Question::where('depends_on', $motherQuestion->id)
                ->get()
                ->filter(function ($sonQuestion) use ($motherQuestionAnswer) {
                    return in_array($motherQuestionAnswer->text, $sonQuestion->showed_when);
                });

            return $sonQuestionsShowed->first(function ($sonQuestion) {
                $sonAnswer = $sonQuestion->current_user_answer;

                return (! $sonAnswer) || ($sonAnswer->is_real === false);
            });
        });
    }
}