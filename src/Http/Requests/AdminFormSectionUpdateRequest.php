<?php

namespace R64\Webforms\Http\Requests;

use Illuminate\Validation\Rule;
use R64\Webforms\Models\FormSection;

class AdminFormSectionUpdateRequest extends JsonFormRequest
{
    private $formSection;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->formSection = $this->route('formSection');

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

        return [
            'sort' => 'nullable|integer',
            'slug' => [
                'nullable',
                'string',
                Rule::unique($formSectionsTable)->ignore($this->formSection, 'slug'),
            ],
            'menu_title' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    public function validationData()
    {
        return [
            'sort' => $this->sort ? $this->sort : $this->formSection->sort,
            'slug' => $this->slug ? $this->slug : $this->formSection->slug,
            'menu_title' => $this->menu_title ? $this->menu_title : $this->formSection->menu_title,
            'title' => $this->title ? $this->title : $this->formSection->title,
            'description' => $this->description ? $this->description : $this->formSection->description,
        ];
    }
}
