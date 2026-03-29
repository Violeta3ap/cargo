<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Klientu tabulas modelis.
class Klienti extends Model
{
    // Tabulas iestatījumi.
    protected $table = 'klienti';
    protected $primaryKey = 'KlientaID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
}