<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noma extends Model
{
   protected $table = 'vagonunoma';
    protected $primaryKey = 'NomasID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}