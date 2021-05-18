<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;

class AdminFormStepStoreRequest extends JsonFormRequest
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
        $formsTable = (new Form)->getTable();
        $formStepsTable = (new FormStep)->getTable();

        return [
            'form_id' => 'required|exists:' . $formsTable . ',id',
            'sort' => 'nullable|integer',
            'slug' => 'nullable|string|unique:' . $formStepsTable . ',slug',
            'menu_title' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'is_personal_data' => 'nullable|boolean',
        ];
    }

    public function validationData()
    {
        return [
            'form_id' => $this->form_id,
            'sort' => $this->sort ? $this->sort : FormStep::getLastSort($this->form_id),
            'slug' => $this->slug ? $this->slug : FormStep::getSlugFromTitle($this->title),
            'menu_title' => $this->menu_title,
            'title' => $this->title,
            'description' => $this->description,
            'is_personal_data' => $this->is_personal_data ?? FormStep::getDefaultIsPersonalData(),
        ];
    }
}
