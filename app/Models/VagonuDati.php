<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VagonuDati extends Model
{
   protected $table = 'vagonudati';   // Tabulas nosaukums datubāzē
   protected $primaryKey = 'DatuID';   // Primārais atslēgas lauks
   protected $keyType = 'int';         // Primārās atslēgas tips ir skaitlis
   public $incrementing = true;        // ID automātiski palielinās ar katru ierakstu
   public $timestamps = false;         // Nav created_at un updated_at lauku
}