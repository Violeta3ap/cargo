<?php

namespace App\Models;

use App\Models\Veidi;
use Illuminate\Database\Eloquent\Model;

/**
 * Kravas modelis - Pārstāv kravu (kravas tipu) ierakstus datu bāzē
 * 
 * Kravaas ir konkrēts preču tips/kategorija, kas tiek transportēta (piemēram: "Grauds", "Metāls", "Ogas")
 * Katra kravaā ir saistīta ar vienu vagona veidu (kuram vagonu veidam ar šo preču var transportēt)
 * 
 * Datu bāzes tabula: krava
 * Primārā atslēga: KravasID
 */
class Kravas extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'krava';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'KravasID';

    /**
     * Primārās atslēgas datu tips
     * 
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Vai primārā atslēga ir auto-increment
     * 
     * @var bool
     */
    public $incrementing = true;

    /**
     * Vai modelis izmanto created_at un updated_at laikus
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Saistība ar vagona veidu - "Pieder" vienam vagona veidam
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function veidi()
    {
        // Kravaā satur VeidaID, kas norāda uz Veidi modeli
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }
}
