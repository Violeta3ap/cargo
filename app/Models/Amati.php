<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amati extends Model
{
   protected $table = 'amats';
    protected $primaryKey = 'AmataID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
