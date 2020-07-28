<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FormStepUser extends Pivot
{
    protected $casts = [
        'completed' => 'boolean',
    ];
}
