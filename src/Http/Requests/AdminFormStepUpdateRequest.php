<?php

namespace R64\Webforms\Http\Requests;

use Illuminate\Validation\Rule;
use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;

class AdminFormStepUpdateRequest extends JsonFormRequest
{
    public $formStep;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->formStep = $this->route('formStep');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $formsTable = (new Form)->getTable();
        $formStepsTable = (new FormStep)->getTable();

        return [
            'form_id' => 'nullable|exists:' . $formsTable . ',id',
            'sort' => 'nullable|integer',
            'slug' => [
                'nullable',
                'string',
                Rule::unique($formStepsTable)->ignore($this->formStep, 'slug'),
            ],
            'menu_title' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'is_personal_data' => 'nullable|boolean',
        ];
    }

    public function validationData()
    {
        return [
            'form_id' => $this->form_id ? $this->form_id : $this->formStep->form_id,
            'sort' => $this->sort ? $this->sort : $this->formStep->sort,
            'slug' => $this->slug ? $this->slug : $this->formStep->slug,
            'menu_title' => $this->menu_title ? $this->menu_title : $this->formStep->menu_title,
            'title' => $this->title ? $this->title : $this->formStep->title,
            'description' => $this->description ? $this->description : $this->formStep->description,
            'is_personal_data' => $this->is_personal_data ? $this->is_personal_data : $this->formStep->is_personal_data,
        ];
    }
}
