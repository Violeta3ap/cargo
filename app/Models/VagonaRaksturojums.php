<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VagonaRaksturojums extends Model
{
   protected $table = 'vagonaraksturojums';
    protected $primaryKey = 'VagonaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
