<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Helpers\Slug;
use R64\Webforms\Models\FormSection;
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
        $formSectionsTable = (new FormSection)->getTable();
        $formStepsTable = (new FormStep)->getTable();

        return [
            'form_section_id' => 'required|exists:' . $formSectionsTable . ',id',
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
        $formSection = FormSection::findOrFail($this->form_section_id);

        $lastSort = $formSection->formSteps()->max('sort') ?? 0;
        $sort = ((int)$lastSort) + 1;
        $slug = Slug::make($this->title, (new FormStep)->getTable());

        return [
            'form_section_id' => $this->form_section_id,
            'sort' => $this->sort ? $this->sort : $sort,
            'slug' => $this->slug ? $this->slug : $slug,
            'menu_title' => $this->menu_title,
            'title' => $this->title,
            'description' => $this->description,
            'is_personal_data' => $this->is_personal_data ?? 0,
        ];
    }
}
