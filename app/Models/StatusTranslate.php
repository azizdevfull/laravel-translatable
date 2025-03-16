<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTranslate extends Model
{
    public $timestamps = false; // Tarjimaga vaqt markeri kerak bo‘lmasa
    protected $fillable = ['locale', 'name'];
}
