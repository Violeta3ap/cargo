<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Darbinieki extends Model
{
   protected $table = 'darbinieki';
    protected $primaryKey = 'DarbiniekaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
