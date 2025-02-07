<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Translatable
{
    public function scopeWhereTranslationLike(Builder $query, $column, $value, $locale = null)
    {
        $locale = $locale ?? app()->getLocale(); // Default til
        return $query->whereHas('translations', function ($query) use ($column, $value, $locale) {
            $query->where('locale', $locale)->where($column, 'LIKE', $value);
        });
    }

    public function scopeOrWhereTranslationLike(Builder $query, $column, $value, $locale = null)
    {
        $locale = $locale ?? app()->getLocale(); // Default til
        return $query->orWhereHas('translations', function ($query) use ($column, $value, $locale) {
            $query->where('locale', $locale)->where($column, 'LIKE', $value);
        });
    }
}
