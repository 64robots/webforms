<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Models\Answer;
use R64\Webforms\Models\Question;

class AnswerStoreRequest extends JsonFormRequest
{
    public $question;
    public $answer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->question = Question::find($this->question_id);
        $this->answer = Answer::where('user_id', auth()->user()->id)
            ->where('question_id', $this->question_id)
            ->first();

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var string $userClass */
        $userClass = config('webforms.user_model');
        $usersTable = (new $userClass)->getTable();
        $questionsTable = (new Question)->getTable();

        return [
            'user_id' => 'required|exists:' . $usersTable . ',id',
            'question_id' => 'required|integer|exists:' . $questionsTable . ',id',
            'text' => 'required|' . $this->question->getValidationRules(),
        ];
    }

    public function validationData()
    {
        return [
            'user_id' => auth()->user()->id,
            'question_id' => $this->question_id,
            'text' => $this->text,
        ];
    }
}
