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

    public static function reorderCollection($collection, $sort, $column = 'sort')
    {
        if ($collection->where($column, $sort)->first()) {
            $collection->where($column, '>=', $sort)->each(function ($element) use ($column) {
                $element->{$column} = $element->{$column} + 1;
                $element->save();
            });
        }

        return $sort;
    }
}
