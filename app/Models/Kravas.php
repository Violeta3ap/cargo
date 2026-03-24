<?php

namespace App\Models;
use App\Models\Veidi;

use Illuminate\Database\Eloquent\Model;

class Kravas extends Model
{
    protected $table = 'krava'; // Modelis saistīts ar datu bāzes tabulu 'krava'
    protected $primaryKey = 'KravasID'; // Tabulas primārā atslēga
    protected $keyType = 'int'; // Primārās atslēgas datu tips ir vesels skaitlis
    public $incrementing = true; // Primārā atslēga automātiski palielinās (auto-increment)
    public $timestamps = false; // Nesaglabāt laika zīmogus created_at un updated_at

       // Saistība ar Veidi tabulu
   public function veidi()
   {
       return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID'); // Katrs vagons ir konkrēta veida
   }

}
