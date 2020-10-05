<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Helpers\Slug;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;

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
            'type' => 'nullable|string|in:date,year-month,integer,money,age,percent,boolean,options,text,phone,email',
            'post_input_text' => 'nullable|string',
            'title' => 'required|string',
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
        $formStep = FormStep::findOrFail($this->form_step_id);

        $lastSort = $formStep->questions()->max('sort') ?? 0;
        $sort = ((int)$lastSort) + 1;
        $slug = Slug::make($this->title, (new Question)->getTable());

        return [
            'form_step_id' => $this->form_step_id,
            'depends_on' => $this->depends_on,
            'sort' => $this->sort ? $this->sort : $sort,
            'slug' => $this->slug ? $this->slug : $slug,
            'group_by' => $this->group_by,
            'group_by_description' => $this->group_by_description,
            'label_position' => $this->label_position ? $this->label_position : 'top',
            'help_title' => $this->help_title,
            'help_body' => $this->help_body,
            'type' => $this->type ? $this->type : 'text',
            'post_input_text' => $this->post_input_text,
            'title' => $this->title,
            'description' => $this->description,
            'error_message' => $this->error_message,
            'default_value' => $this->default_value,
            'min' => $this->min,
            'max' => $this->max,
            'showed_when' => $this->showed_when,
            'options' => $this->options,
            'required' => $this->required ? $this->required : 0,
        ];
    }
}
