<?php

namespace App\Models;
use App\Models\Amati;
use App\Models\Noma;
use Illuminate\Database\Eloquent\Model;

class Darbinieki extends Model
{
   protected $table = 'darbinieki';
    protected $primaryKey = 'DarbiniekaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

      public function amati()
    {
        return $this->belongsTo(Amati::class, 'AmataID', 'AmataID');
        return $this->belongsTo(Noma::class, 'NomasID', 'NomasID');
    }

    
}
