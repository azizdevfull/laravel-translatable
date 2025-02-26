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
    // public function setTranslations(array $translations)
    // {
    //     $data = [];
    //     foreach ($translations as $locale => $values) {
    //         $values['locale'] = $locale;
    //         $values['post_id'] = $this->id; // Postga bog‘lash
    //         $data[] = $values;
    //     }

    //     PostTranslation::upsert($data, ['post_id', 'locale'], ['title', 'content']);
    // }


}
