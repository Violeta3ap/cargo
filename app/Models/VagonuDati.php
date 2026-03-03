<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VagonuDati extends Model
{
   protected $table = 'vagonudati';
    protected $primaryKey = 'DatuID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}
