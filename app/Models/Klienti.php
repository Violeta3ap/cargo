<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klienti extends Model
{
   protected $table = 'klienti';
    protected $primaryKey = 'KlientaID'; 
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
