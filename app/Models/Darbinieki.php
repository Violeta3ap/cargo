<?php

namespace App\Models;
use App\Models\Amati; // Iekļaujam Amati modeli, lai veidotu saistību
use Illuminate\Database\Eloquent\Model;

class Darbinieki extends Model
{
    protected $table = 'darbinieki'; // Saistīt Eloquent modeli ar datu bāzes tabulu 'darbinieki'
    protected $primaryKey = 'DarbiniekaID'; // Norāda primāro atslēgu tabulā
    protected $keyType = 'int'; // Primārās atslēgas tips ir vesels skaitlis
    public $incrementing = true; // Primārā atslēga automātiski palielinās (auto-increment)
    public $timestamps = false; // Nesaglabāt laika zīmogus created_at un updated_at

    // Funkcija, lai saistītu darbinieku ar viņa amatu
    public function amati()
    {
        return $this->belongsTo(Amati::class, 'AmataID', 'AmataID'); 
        // 'AmataID' tabulā 'darbinieki' → saistīts ar 'AmataID' tabulā 'amats'
    }
}
