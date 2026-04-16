<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Noslogojums modelis - Pārstāv noslogojuma ierakstus
 * 
 * Noslogojums sinhronizēts ar nomām un aprēķina vagonu slodzi (occupancy) konkrētā periodā
 * Šis modelis tiek izmantots, lai ātri atrastu, cik vagoni ir aizņemti konkrētajā datumā
 * 
 * Datu bāzes tabula: noslogojums
 * Primārā atslēga: NoslogojumaID
 */
class Noslogojums extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'noslogojums';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'NoslogojumaID';

    /**
     * Vai modelis izmanto created_at un updated_at laikus
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Lauki, kurus drīkst aizpildīt masveidā pie create/update
     * Šie lauki ir draudzīgi mass-assign operācijām
     * 
     * @var array
     */
    protected $fillable = [
        'NomasID',
        'NomasSakumaPeriods',
        'NomasBeiguPeriods',
        'VeidaID',
    ];

    /**
     * Saistība ar nomas ierakstu - Noslogojums "pieder" vienai nomai
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function noma()
    {
        return $this->belongsTo(Noma::class, 'NomasID', 'NomasID');
    }

    /**
     * Saistība ar vagona veidu - Noslogojums norāda uz konkrētu vagona veidu
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function veidi()
    {
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }
}
