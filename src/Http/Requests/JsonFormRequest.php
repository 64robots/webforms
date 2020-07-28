<?php

namespace R64\Webforms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JsonFormRequest extends FormRequest
{
    /**
     * Get the URL to redirect to on a validation error.
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @return string|null
     */
    protected function getRedirectUrl()
    {
        return null;
    }
}
