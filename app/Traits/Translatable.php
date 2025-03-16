<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Exception;

trait Translatable
{
    public function scopeWhereTranslationLike(Builder $query, $column, $value, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $query->whereHas('translations', function ($query) use ($column, $value, $locale) {
            $query->where('locale', $locale)->where($column, 'LIKE', "%$value%");
        });
    }

    public function scopeOrWhereTranslationLike(Builder $query, $column, $value, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $query->orWhereHas('translations', function ($query) use ($column, $value, $locale) {
            $query->where('locale', $locale)->where($column, 'LIKE', "%$value%");
        });
    }
    // public function __get($key)
    // {
    //     if (isset($this->translatedAttributes) && in_array($key, $this->translatedAttributes)) {
    //         return optional($this->translation())->$key ?? null;
    //     }

    //     return parent::__get($key);
    // }
    public function toArray()
    {
        $attributes = parent::toArray();

        // Tarjima qilinadigan maydonlarni qo‘shish
        if (isset($this->translatedAttributes)) {
            foreach ($this->translatedAttributes as $attribute) {
                $attributes[$attribute] = optional($this->translation())->$attribute ?? null;
            }
        }

        return $attributes;
    }

    public function setTranslations(array $translations)
    {
        if (!$this->exists) {
            throw new Exception("Model must be saved before setting translations.");
        }

        $foreignKey = $this->getTranslationForeignKey();
        $data = [];

        foreach ($translations as $locale => $values) {
            $values['locale'] = $locale;
            $values[$foreignKey] = $this->id;
            $data[] = $values;
        }

        if (!empty($data)) {
            $this->translations()->upsert($data, [$foreignKey, 'locale'], array_keys(reset($data)));
        }
    }

    public function deleteTranslations($locales = null)
    {
        $query = $this->translations();

        if ($locales) {
            $locales = is_array($locales) ? $locales : [$locales];
            $query->whereIn('locale', $locales);
        }

        return $query->delete();
    }

    protected function getTranslationForeignKey()
    {
        return strtolower(class_basename($this)) . '_id';
    }
    public function translations()
    {
        return $this->hasMany($this->getTranslationModel(), $this->getForeignKey());
    }

    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }
    public function getTranslationAttribute()
    {
        return $this->translation();
    }

    public function __get($key)
    {
        if (isset($this->translatedAttributes) && in_array($key, $this->translatedAttributes)) {
            return optional($this->translation())->$key ?? null;
        }

        return parent::__get($key);
    }
    public function getTranslationModel()
    {
        return $this->translationModel ?? get_class($this) . 'Translation';
    }

    public function getForeignKey()
    {
        return $this->translationForeignKey ?? strtolower(class_basename($this)) . '_id';
    }
}
