<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Webforms\Factories\FormStepFactory;
use R64\Webforms\Helpers\Slug;
use R64\Webforms\Helpers\Sort;

class FormStep extends Model
{
    use SoftDeletes;

    public $guarded = [];

    protected $casts = [
        'is_personal_data' => 'boolean',
    ];

    # Relations

    public function form()
    {
        return $this->belongsTo(Form::class);
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

    public function getMenuTitleFrontendAttribute()
    {
        return empty($this->menu_title) ? $this->title : $this->menu_title;
    }

    public function getIsCompletedByCurrentUserAttribute()
    {
        $userFormStep = $this->users()->find(auth()->user()->id);

        if (is_null($userFormStep)) {
            return null;
        }

        return $userFormStep->pivot->completed;
    }

    # CRUD

    public static function build(Form $form, string $title)
    {
        return FormStepFactory::build($form, $title);
    }

    public static function updateFormStep(FormStep $formStep)
    {
        return FormStepFactory::update($formStep);
    }

    public static function makeOneOrUpdate(array $data, FormStep $formStep = null)
    {
        if ($formStep === null) {
            $formStep = new self;
        }

        /** @var Form $form */
        $form = Form::findOrFail($data['form_id']);
        $formStep->sort = Sort::reorderCollection($form->formSteps, $data['sort'], 'sort', $formStep->sort);
        $formStep->form()->associate($form);
        $formStep->slug = $data['slug'];
        $formStep->menu_title = $data['menu_title'];
        $formStep->title = $data['title'];
        $formStep->description = $data['description'];
        $formStep->is_personal_data = $data['is_personal_data'];

        $formStep->save();

        return $formStep;
    }

    public function deleteMe()
    {
        $this->delete();
    }

    # Form

    public function associateForm(Form $form)
    {
        return $this->form()->associate($form);
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
                    return in_array($motherQuestionAnswer->text, $sonQuestion->shown_when);
                });

            return $sonQuestionsShowed->first(function ($sonQuestion) {
                $sonAnswer = $sonQuestion->current_user_answer;

                return (! $sonAnswer) || ($sonAnswer->is_real === false);
            });
        });
    }

    # Helpers

    public static function getLastSort($form)
    {
        if (is_numeric($form)) {
            $form = Form::findOrFail($form);
        }

        $lastSort = $form->formSteps()->max('sort') ?? 0;

        return ((int)$lastSort) + 1;
    }

    public static function getSlugFromTitle($title)
    {
        return Slug::make($title, (new self)->getTable());
    }

    public static function getDefaultIsPersonalData()
    {
        return 0;
    }
}
