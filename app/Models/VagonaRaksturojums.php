<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kravas;
use App\Models\Veidi;

class VagonaRaksturojums extends Model
{
   // Tabulas iestatījumi.
   protected $table = 'vagonaraksturojums';
   protected $primaryKey = 'VagonaID';
   protected $keyType = 'int';
   public $incrementing = true;
   public $timestamps = false;

   // Saistība ar kravu.
   public function kravas()
   {
       return $this->belongsTo(Kravas::class, 'KravasID', 'KravasID');
   }

   // Saistība ar veidu.
   public function veidi()
   {
       return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
   }
}