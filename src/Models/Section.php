<?php

namespace R64\Webforms\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public $guarded = [];

    # Relations

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    # Getters

    public function getMenuTitleAttribute($value)
    {
        return empty($value) ? $this->title : $value;
    }

    # Steps

    public function addStep(Step $step)
    {
        return $this->steps()->save($step);
    }

    public function removeStep(Step $step)
    {
        return $this->steps()->find($step->id)->delete();
    }
}
