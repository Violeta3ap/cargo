<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomasStatuss extends Model
{
    protected $table = 'NomasStatuss';
    protected $primaryKey = 'StatusaID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';
}
