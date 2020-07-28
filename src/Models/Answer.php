<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use R64\Webforms\Events\AnswerCreated;

class Answer extends Model
{
    protected $casts = [
        'user_id' => 'integer',
        'is_current' => 'boolean',
        'is_real' => 'boolean',
        'confirmed' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (Answer $answer) {
            $question = $answer->question;
            $user = $answer->user;
            if (collect(config('webforms.fields_to_be_confirmed'))->isNotEmpty()) {
                $answer->confirmed = ! in_array($question->type, config('webforms.fields_to_be_confirmed'));
            }
            event(new AnswerCreated($answer, $user));
        });

        static::updating(function (Answer $answer) {
            $question = $answer->question;
            $user = $answer->user;
            if (collect(config('webforms.fields_to_be_confirmed'))->isNotEmpty()) {
                if ($answer->isDirty('text')) {
                    $answer->confirmed = ! in_array($question->type, config('webforms.fields_to_be_confirmed'));
                }
            }
            event(new AnswerCreated($answer, $user));
        });
    }

    # Scopes

    public function scopeCurrent($query)
    {
        return $query->where('answers.is_current', true);
    }

    public function scopeByCurrentUser($query)
    {
        return $query->where('answers.user_id', auth()->user()->id);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('answers.confirmed', true);
    }

    public function scopeReal($query)
    {
        return $query->where('answers.is_real', true);
    }

    public function scopeFictional($query)
    {
        return $query->where('answers.is_real', false);
    }

    # Relations

    public function user()
    {
        return $this->belongsTo(config('webforms.user_model'));
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    # Getters

    public function getTextAttribute($value)
    {
        if ($this->question->formStep->is_personal_data) {
            $value = decrypt($value);
        }

        return $this->question->cast($value);
    }

    public function getFrontTextAttribute()
    {
        return $this->question->castToFront($this->text);
    }

    # Setters

    public function setTextAttribute($value)
    {
        $this->attributes['text'] = $value;

        if ($this->question->formStep->is_personal_data) {
            $this->attributes['text'] = encrypt($value);
        }
    }

    # Crud

    public static function makeOneOrUpdate(array $data, ?Answer $answer = null)
    {
        $data['is_real'] = true;
        /** @var Model $userClass */
        $userClass = config('webforms.user_model');
        /** @var \R64\Webforms\Tests\Feature\Models\User $user */
        $user = $userClass::find($data['user_id']);

        if (! $answer) {
            return self::makeOne($data, $user);
        }

        $answer->text = $data['text'];
        $answer->is_real = $data['is_real'];

        $answer->update();

        $answer->refreshFormStepCompletedStatus();

        return $answer;
    }

    private static function makeOne($data, $user)
    {
        $question = Question::find($data['question_id']);
        $answer = new self;
        $answer->user()->associate($user);
        $answer->question()->associate($question);
        $answer->text = $data['text'];
        $answer->is_real = $data['is_real'];
        $answer->save();

        $answer->refreshFormStepCompletedStatus();

        return $answer;
    }

    public function deleteMe()
    {
        $this->delete();

        $this->refreshFormStepCompletedStatus();
    }

    public static function makeOneFictional($user, Question $question)
    {
        $answer = new self;
        $answer->user()->associate($user);
        $answer->question()->associate($question);
        $answer->text = $question->default_value;
        $answer->is_real = false;

        $answer->save();

        return $answer;
    }

    # Helpers

    public function markAsReal()
    {
        $this->is_real = true;

        $this->save();

        return $this;
    }

    private function refreshFormStepCompletedStatus()
    {
        $formStep = $this->question->formStep;

        $uncompletedRequiredQuestions = $formStep->questionsWithoutCurrentUserAnswer()->exists();

        $uncompletedSonQuestions = $formStep->uncompletedSonQuestions();

        $uncompletedStatus = $uncompletedRequiredQuestions || $uncompletedSonQuestions;

        $uncompletedStatus
            ? $this->user->markFormStepAsUncompleted($formStep)
            : $this->user->markFormStepAsCompleted($formStep);
    }
}
