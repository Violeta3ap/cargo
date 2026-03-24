<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;

class Noma extends Model
{
    protected $table = 'vagonunoma'; // Modelis saistīts ar tabulu 'vagonunoma'
    protected $primaryKey = 'NomasID'; // Tabulas primārā atslēga
    protected $keyType = 'int'; // Primārās atslēgas datu tips ir vesels skaitlis
    public $incrementing = true; // Primārā atslēga automātiski palielinās (auto-increment)
    public $timestamps = false; // Nesaglabāt created_at un updated_at laika zīmogus

    // Saistība ar klientu tabulu (viens noma pieder vienam klientam)
    public function klienti()
    {
        return $this->belongsTo(Klienti::class, 'KlientaID', 'KlientaID');
    }

    // Saistība ar darbinieku tabulu (viena noma pieder vienam darbiniekam)
 

    // Saistība ar kravu tabulu (viena noma pieder vienai kravai)
    public function kravas()
    {
        return $this->belongsTo(Kravas::class, 'KravasID', 'KravasID');
    }

        public function veidi()
    {
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }
}
