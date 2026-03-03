<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kravas extends Model
{
   protected $table = 'krava';
    protected $primaryKey = 'KravasID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
