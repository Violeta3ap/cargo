<?php

namespace App\Models;

use App\Models\Veidi;
use Illuminate\Database\Eloquent\Model;

// Kravas tabulas modelis.
class Kravas extends Model
{
    // Tabulas iestatījumi.
    protected $table = 'krava';
    protected $primaryKey = 'KravasID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    // Saistība ar veidu.
    public function veidi()
    {
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }

}
