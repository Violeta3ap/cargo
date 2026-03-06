<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VagonuDati;

class VagonaRaksturojums extends Model
{
   protected $table = 'vagonaraksturojums';
    protected $primaryKey = 'VagonaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;


      public function vagonudati()
    {
        return $this->belongsTo(VagonuDati::class, 'VagonaID', 'VagonaID');
    }
    

    
}
