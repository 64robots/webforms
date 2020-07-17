<?php

namespace R64\Webforms\Helpers;

class Options
{
    public static function transform($options)
    {
        return collect($options)->map(function ($option, $key) {
            return [
                'label' => $option,
                'value' => $key,
            ];
        })->values();
    }
}
