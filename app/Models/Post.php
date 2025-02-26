<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Translatable;

    protected $fillable = ['status'];
    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
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

}
