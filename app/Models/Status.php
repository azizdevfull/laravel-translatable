<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use Translatable;

    protected $translatedAttributes = ['name']; // Tarjima qilinadigan maydonlar

}
