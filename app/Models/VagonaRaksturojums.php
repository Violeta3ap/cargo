<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kravas;
use App\Models\Veidi;

class VagonaRaksturojums extends Model
{
   protected $table = 'vagonaraksturojums'; // Tabulas nosaukums datubāzē
   protected $primaryKey = 'VagonaID';       // Primārais atslēgas lauks
   protected $keyType = 'int';               // Primārās atslēgas tips
   public $incrementing = true;              // ID automātiski palielinās
   public $timestamps = false;               // Nav created_at un updated_at lauku

   // Saistība ar Kravas tabulu
   public function kravas()
   {
       return $this->belongsTo(Kravas::class, 'KravasID', 'KravasID'); // Katrs vagons pieder konkrētai kravai
   }

   // Saistība ar Veidi tabulu
   public function veidi()
   {
       return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID'); // Katrs vagons ir konkrēta veida
   }
}