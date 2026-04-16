<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Klienti modelis - Pārstāv klientu ierakstus datu bāzē
 * 
 * Klients ir uzņēmums vai privātpersona, kas nolīgt vagone
 * Satur klients informāciju: vārdu, uzvārdu, e-pastu, telefona numuru, uzņēmuma datus utt.
 * 
 * Datu bāzes tabula: klienti
 * Primārā atslēga: KlientaID
 */
class Klienti extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'klienti';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'KlientaID';

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