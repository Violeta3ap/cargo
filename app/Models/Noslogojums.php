<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Noslogojuma tabulas modelis.
class Noslogojums extends Model
{
    // Datubāzes tabulas nosaukums.
    protected $table = 'noslogojums';

    // Primārās atslēgas kolonna.
    protected $primaryKey = 'NoslogojumaID';

    // Tabulai nav created_at un updated_at kolonnu.
    public $timestamps = false;

    // Lauki, kurus drīkst aizpildīt masveidā.
    protected $fillable = [
        'NomasID',
        'NomasSakumaPeriods',
        'NomasBeiguPeriods',
        'VeidaID',
    ];

    // Saistība ar nomas ierakstu.
    public function noma()
    {
        return $this->belongsTo(Noma::class, 'NomasID', 'NomasID');
    }

    // Saistība ar vagona veida ierakstu.
    public function veidi()
    {
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }
}
