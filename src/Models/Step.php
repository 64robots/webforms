<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public $guarded = [];

    # Relations

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    # Getters

    public function getMenuTitleAttribute($value)
    {
        return empty($value) ? $this->title : $value;
    }

    # Section

    public function associateSection(Section $section)
    {
        return $this->section()->associate($section);
    }
}
