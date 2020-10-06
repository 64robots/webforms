<?php

namespace R64\Webforms\Helpers;

use Illuminate\Support\Facades\DB;

class Sort
{
    public static function reorder($sort, $table, $column = 'sort', $ignoreSort = null)
    {
        $sortExists = DB::table($table)
            ->where($column, $sort)
            ->where($column, '!=', $ignoreSort)
            ->exists();

        if ($sortExists) {
            DB::table($table)->where($column, '>=', $sort)->increment($column);
        }

        return $sort;
    }

    public static function reorderCollection($collection, $sort, $column = 'sort', $ignoreSort = null)
    {
        $sortExists = $collection
            ->where($column, $sort)
            ->where($column, '!=', $ignoreSort)
            ->first();

        if ($sortExists) {
            $collection->where($column, '>=', $sort)->each(function ($element) use ($column) {
                $element->{$column} = $element->{$column} + 1;
                $element->save();
            });
        }

        return $sort;
    }
}
