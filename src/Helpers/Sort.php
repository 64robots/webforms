<?php

namespace R64\Webforms\Helpers;

use Illuminate\Support\Facades\DB;

class Sort
{
    public static function reorder($sort, $table, $column = 'sort')
    {
        if (DB::table($table)->where($column, $sort)->exists()) {
            DB::table($table)->where($column, '>=', $sort)->increment($column);
        }

        return $sort;
    }
}
