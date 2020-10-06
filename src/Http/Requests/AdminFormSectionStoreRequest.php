<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Helpers\Slug;
use R64\Webforms\Models\FormSection;

class AdminFormSectionStoreRequest extends JsonFormRequest
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

        return [
            'sort' => 'nullable|integer',
            'slug' => 'nullable|string|unique:' . $formSectionsTable . ',slug',
            'menu_title' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function validationData()
    {
        return [
            'sort' => $this->sort ? $this->sort : FormSection::getLastSort(),
            'slug' => $this->slug ? $this->slug : FormSection::getSlugFromTitle($this->title),
            'menu_title' => $this->menu_title,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
