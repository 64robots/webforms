<?php

namespace R64\Webforms\Http\Requests;

use R64\Webforms\Helpers\Slug;
use R64\Webforms\Models\FormSection;

class StoreFormSectionRequest extends JsonFormRequest
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
        $lastSort = FormSection::max('sort') ?? 0;
        $sort = ((int)$lastSort) + 1;
        $slug = Slug::make($this->title, (new FormSection())->getTable());

        return [
            'sort' => $this->sort ? $this->sort : $sort,
            'slug' => $this->slug ? $this->slug : $slug,
            'menu_title' => $this->menu_title,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
