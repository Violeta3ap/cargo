<?php
// PHP faila sākums.

// Modeļa nosaukumvieta.
namespace App\Models;

// Bāzes Eloquent modelis.
use Illuminate\Database\Eloquent\Model;

// Veidu tabulas modelis.
class Veidi extends Model
{
   // Datubāzes tabulas nosaukums.
   protected $table = 'veidi';

   // Primārās atslēgas kolonna tabulā.
   protected $primaryKey = 'VeidaID';

   // Primārās atslēgas datu tips.
   protected $keyType = 'int';

   // Primārā atslēga ir auto-increment.
   public $incrementing = true;

   // Tabulai nav created_at un updated_at kolonnu.
   public $timestamps = false;
}