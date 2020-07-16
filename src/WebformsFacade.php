<?php

namespace R64\Webforms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \R64\Webforms\Webforms
 */
class WebformsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'webforms';
    }
}
