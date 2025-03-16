<?php

namespace App\Models;

use App\Models\PostTranslation;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Translatable;

    public $translatedAttributes = ['title', 'content']; // Tarjima qilinadigan maydonlar
    protected $fillable = ['status'];
    // Custom model va foreign key
    // protected $translationModel = PostTranslation::class; // Custom model
    // protected $translationForeignKey = 'post_id'; // Custom foreign key
}
