<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veidi extends Model
{
   protected $table = 'veidi';        // Tabulas nosaukums datubāzē
   protected $primaryKey = 'VeidaID';  // Primārais atslēgas lauks
   protected $keyType = 'int';         // Primārās atslēgas tips ir skaitlis
   public $incrementing = true;        // ID automātiski palielinās ar katru ierakstu
   public $timestamps = false;         // Nav created_at un updated_at lauku
}