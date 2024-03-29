<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Models\QuestionTypes;

class AdminQuestionStoreRequest extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'form_step_id' => 'required|exists:' . $formStepsTable . ',id',
            'depends_on' => 'nullable|exists:' . $questionsTable . ',id',
            'sort' => 'nullable|integer',
            'slug' => 'nullable|string|unique:' . $questionsTable . ',slug',
            'group_by' => 'nullable|string',
            'group_by_description' => 'nullable|string',
            'label_position' => 'nullable|string|in:top,left,right',
            'help_title' => 'nullable|string',
            'help_body' => 'nullable|string',
            'type' => 'nullable|string|in:' . QuestionTypes::getAllQuestionTypes()->implode(','),
            'post_input_text' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'error_message' => 'nullable|string',
            'default_value' => 'nullable|string',
            'min' => 'nullable|string',
            'max' => 'nullable|string',
            'shown_when' => 'nullable|array',
            'options' => 'nullable|array',
            'required' => 'nullable|boolean',
        ];
    }

    public function validationData()
    {
        return [
            'form_step_id' => $this->form_step_id,
            'depends_on' => $this->depends_on,
            'sort' => $this->sort ? $this->sort : Question::getLastSort($this->form_step_id),
            'slug' => $this->slug ? $this->slug : Question::getSlugFromTitle($this->title),
            'group_by' => $this->group_by,
            'group_by_description' => $this->group_by_description,
            'label_position' => $this->label_position ? $this->label_position : Question::getDefaultLabelPosition(),
            'help_title' => $this->help_title,
            'help_body' => $this->help_body,
            'type' => $this->type ? $this->type : Question::getDefaultType(),
            'post_input_text' => $this->post_input_text,
            'title' => $this->title,
            'description' => $this->description,
            'error_message' => $this->error_message,
            'default_value' => $this->default_value,
            'min' => $this->min,
            'max' => $this->max,
            'shown_when' => $this->shown_when,
            'options' => $this->options,
            'required' => $this->required ? $this->required : Question::getDefaultRequired(),
        ];
    }
}
