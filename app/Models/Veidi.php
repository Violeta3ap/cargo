<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veidi extends Model
{
   protected $table = 'veidi';
    protected $primaryKey = 'VeidaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
