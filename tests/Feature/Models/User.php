<?php

namespace R64\Webforms\Tests\Feature\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use R64\Webforms\Traits\Formable;
use R64\Webforms\Traits\HasWebForms;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasWebForms;
    use Formable;
    use Authorizable;
    use Authenticatable;

    protected $fillable = ['email'];

    public $timestamps = false;

    protected $table = 'users';
}
