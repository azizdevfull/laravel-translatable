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
    public function setTranslations(array $translations)
    {
        if (!$this->id) {
            throw new \Exception("Model must be saved before setting translations.");
        }

        $data = [];
        $foreignKey = $this->getForeignKey(); // Bog‘liq modelning foreign key maydoni

        foreach ($translations as $locale => $values) {
            $values['locale'] = $locale;
            $values[$foreignKey] = $this->id; // Modelni bog‘lash
            $data[] = $values;
        }

        $this->translations()->upsert($data, [$foreignKey, 'locale'], array_keys($values));
    }
    public function deleteTranslations($locales = null)
    {
        $query = $this->translations();

        if ($locales) {
            $locales = is_array($locales) ? $locales : [$locales]; // Agar string bo‘lsa, arrayga aylantiramiz
            $query->whereIn('locale', $locales);
        }

        return $query->delete();
    }


}
