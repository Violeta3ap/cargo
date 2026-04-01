<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaksasStatuss extends Model
{
    protected $table = 'MaksasStatuss';
    protected $primaryKey = 'MaksasID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';
}
