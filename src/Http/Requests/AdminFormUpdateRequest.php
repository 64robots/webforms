<?php

namespace R64\Webforms\Http\Requests;

use Illuminate\Validation\Rule;
use R64\Webforms\Models\Form;

class AdminFormUpdateRequest extends JsonFormRequest
{
    private $form;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->form = $this->route('form');

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

        return [
            'sort' => 'nullable|integer',
            'slug' => [
                'nullable',
                'string',
                Rule::unique($formsTable)->ignore($this->form, 'slug'),
            ],
            'menu_title' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    public function validationData()
    {
        return [
            'sort' => $this->sort ? $this->sort : $this->form->sort,
            'slug' => $this->slug ? $this->slug : $this->form->slug,
            'menu_title' => $this->menu_title ? $this->menu_title : $this->form->menu_title,
            'title' => $this->title ? $this->title : $this->form->title,
            'description' => $this->description ? $this->description : $this->form->description,
        ];
    }
}
