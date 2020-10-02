<?php

namespace R64\Webforms\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Slug
{
    public static function make($text, $table, $column = 'slug', $iteration = 0)
    {
        $append = $iteration ? '-' . $iteration : '';
        $slug = Str::slug($text) . $append;

        if (DB::table($table)->where($column, $slug)->exists()) {
            return self::make($text, $table, $column, $iteration + 1);
        }

        return $slug;
    }
}
