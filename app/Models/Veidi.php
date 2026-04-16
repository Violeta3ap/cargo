<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Veidi modelis - Pārstāv vagona veidu ierakstus
 * 
 * Vagona veids ir vagona tipologija/klasifikācija (piemēram: "Jumta vagons", "Kravas vagons", "Termiski kontrolēts vagons")
 * Katrs vagona veids satur:
 * - Celtspēju (maksimālā slodze tonnas)
 * - Kopējo vagonu skaitu nolietojumā
 * - Nomas cenu par dienu
 * 
 * Datu bāzes tabula: veidi
 * Primārā atslēga: VeidaID
 */
class Veidi extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'veidi';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'VeidaID';

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
}