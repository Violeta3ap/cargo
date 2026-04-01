<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;
use App\Models\NomasStatuss;
use App\Models\MaksasStatuss;

// Nomas tabulas modelis.
class Noma extends Model
{
    // Tabulas iestatījumi.
    protected $table = 'vagonunoma';
    protected $primaryKey = 'NomasID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    // Saistība ar klientu.
    public function klienti()
    {
        return $this->belongsTo(Klienti::class, 'KlientaID', 'KlientaID');
    }

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

    // Saistība ar nomas statusu.
    public function nomasStatuss()
    {
        return $this->belongsTo(NomasStatuss::class, 'StatusaID', 'StatusaID');
    }

    // Saistība ar maksas statusu.
    public function maksasStatuss()
    {
        return $this->belongsTo(MaksasStatuss::class, 'MaksasID', 'MaksasID');
    }
}
