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

    public function isGet()
    {
        if ($this->method() == 'GET') {
            return true;
        }

        return false;
    }

    protected function isPost()
    {
        if ($this->method() == 'POST') {
            return true;
        }

        return false;
    }

    protected function isPut()
    {
        if ($this->method() == 'PUT') {
            return true;
        }

        return false;
    }

    protected function isDelete()
    {
        if ($this->method() == 'DELETE') {
            return true;
        }

        return false;
    }
}
