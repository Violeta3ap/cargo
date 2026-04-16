<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Klienti;
use App\Models\Kravas;
use App\Models\Veidi;
use App\Models\NomasStatuss;
use App\Models\MaksasStatuss;

/**
 * Noma modelis - Pārstāv vagonu nomas ierakstus
 * 
 * Noma ir vienīgais galvenais objekts sistēmā. Tā satur informāciju par:
 * - Kuri vagoni tiek nolīgti (vagona veids - VeidaID)
 * - Kādu preču transportē (kravaā - KravasID)
 * - Kuram klientam (klients - KlientaID)
 * - Uz kādu periodu (sākuma un beigu datumi)
 * - Cik vagonu (skaits)
 * - Kāda ir kopējā maksa
 * - Kāds ir nomas statuss (apstiprināta/noraidīta/izskatīšanā)
 * - Kāds ir maksas statuss (apmaksāta/nav apmaksāta)
 * 
 * Datu bāzes tabula: vagonunoma
 * Primārā atslēga: NomasID
 */
class Noma extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'vagonunoma';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'NomasID';

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
     * Saistība ar klientu - Noma "pieder" vienam klientam
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function klienti()
    {
        return $this->belongsTo(Klienti::class, 'KlientaID', 'KlientaID');
    }

    /**
     * Saistība ar kravu - Noma transportē vienu kravu tipu
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kravas()
    {
        return $this->belongsTo(Kravas::class, 'KravasID', 'KravasID');
    }

    /**
     * Saistība ar vagona veidu - Noma satur konkrētu vagona veidu
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function veidi()
    {
        return $this->belongsTo(Veidi::class, 'VeidaID', 'VeidaID');
    }

    /**
     * Saistība ar nomas statusu - Noma ir konkrētā statusā (apstiprināta/noraidīta/utt)
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nomasStatuss()
    {
        return $this->belongsTo(NomasStatuss::class, 'StatusaID', 'StatusaID');
    }

    /**
     * Saistība ar maksas statusu - Noma ir konkrētā maksas statusā (apmaksāta/nav apmaksāta/utt)
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maksasStatuss()
    {
        return $this->belongsTo(MaksasStatuss::class, 'MaksasID', 'MaksasID');
    }
}
