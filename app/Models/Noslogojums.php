<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noslogojums extends Model
{
    protected $table = 'noslogojums';
    protected $primaryKey = 'NoslogojumaID';
    public $timestamps = false;

    protected $fillable = [
        'NomasID',
        'NomasSakumaPeriods',
        'NomasBeiguPeriods',
        'VeidaID',
    ];

    public function noma()
    {
        return $this->belongsTo(Noma::class, 'NomasID', 'NomasID');
    }

    public function veidi()
    {
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }
}
