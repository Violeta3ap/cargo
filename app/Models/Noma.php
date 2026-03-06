<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Klienti;
use App\Models\Darbinieki;
use App\Models\Kravas;



use Illuminate\Database\Eloquent\Model;

class Noma extends Model
{
   protected $table = 'vagonunoma';
    protected $primaryKey = 'NomasID';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;





 
    public function klienti()
    {
        return $this->belongsTo(Klienti::class, 'KlientaID', 'KlientaID');
    }
 
    public function darbinieki()
    {
        return $this->belongsTo(Darbinieki::class, 'DarbiniekaID', 'DarbiniekaID');
    }
 
    public function kravas()
    {
        return $this->belongsTo(Kravas::class, 'KravasID', 'KravasID');
    }
 


    
}
