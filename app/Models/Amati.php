<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amati extends Model
{
    // Tabulas iestatījumi.
    protected $table = 'amats';
    protected $primaryKey = 'AmataID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}