<?php

namespace R64\Webforms\Traits;

use R64\Webforms\Models\FormSection;

trait FormSectionable
{
    public function formSection()
    {
        return $this->morphOne(FormSection::class, 'form_sectionable');
    }

    public function associateFormSection(FormSection $formSection)
    {
        return $this->formSection()->save($formSection);
    }
}
