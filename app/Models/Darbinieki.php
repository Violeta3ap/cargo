<?php

namespace App\Models;

use App\Models\Amati;
use Illuminate\Database\Eloquent\Model;

class Darbinieki extends Model
{
    // Tabulas iestatījumi.
    protected $table = 'darbinieki';
    protected $primaryKey = 'DarbiniekaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    // Saistība ar amatu.
    public function amati()
    {
        return $this->belongsTo(Amati::class, 'AmataID', 'AmataID');
    }
}
