<?php

namespace R64\Webforms\Http\Requests;

use Illuminate\Validation\Rule;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Models\QuestionTypes;

class AdminQuestionUpdateRequest extends JsonFormRequest
{
    public $question;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->question = $this->route('question');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $formStepsTable = (new FormStep)->getTable();
        $questionsTable = (new Question)->getTable();

        return [
            'form_step_id' => 'nullable|exists:' . $formStepsTable . ',id',
            'depends_on' => 'nullable|exists:' . $questionsTable . ',id',
            'sort' => 'nullable|integer',
            'slug' => [
                'nullable',
                'string',
                Rule::unique($questionsTable)->ignore($this->question, 'slug'),
            ],
            'group_by' => 'nullable|string',
            'group_by_description' => 'nullable|string',
            'label_position' => 'nullable|string|in:top,left,right',
            'help_title' => 'nullable|string',
            'help_body' => 'nullable|string',
            'type' => 'nullable|string|in:' . QuestionTypes::getAllQuestionTypes()->implode(','),
            'post_input_text' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'error_message' => 'nullable|string',
            'default_value' => 'nullable|string',
            'min' => 'nullable|string',
            'max' => 'nullable|string',
            'showed_when' => 'nullable|array',
            'options' => 'nullable|array',
            'required' => 'nullable|boolean',
        ];
    }

    public function validationData()
    {
        return [
            'form_step_id' => $this->form_step_id ? $this->form_step_id : $this->question->form_step_id,
            'depends_on' => $this->depends_on ? $this->depends_on : $this->question->depends_on,
            'sort' => $this->sort ? $this->sort : $this->question->sort,
            'slug' => $this->slug ? $this->slug : $this->question->slug,
            'group_by' => $this->group_by ? $this->group_by : $this->question->group_by,
            'group_by_description' => $this->group_by_description ? $this->group_by_description : $this->question->group_by_description,
            'label_position' => $this->label_position ? $this->label_position : $this->question->label_position,
            'help_title' => $this->help_title ? $this->help_title : $this->question->help_title,
            'help_body' => $this->help_body ? $this->help_body : $this->question->help_body,
            'type' => $this->type ? $this->type : $this->question->type,
            'post_input_text' => $this->post_input_text ? $this->post_input_text : $this->question->post_input_text,
            'title' => $this->title ? $this->title : $this->question->title,
            'description' => $this->description ? $this->description : $this->question->description,
            'error_message' => $this->error_message ? $this->error_message : $this->question->error_message,
            'default_value' => $this->default_value ? $this->default_value : $this->question->default_value,
            'min' => $this->min ? $this->min : $this->question->min,
            'max' => $this->max ? $this->max : $this->question->max,
            'showed_when' => $this->showed_when ? $this->showed_when : $this->question->showed_when,
            'options' => $this->options ? $this->options : $this->question->options,
            'required' => $this->required ? $this->required : $this->question->required,
        ];
    }
}
