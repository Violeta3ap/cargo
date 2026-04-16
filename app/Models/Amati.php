<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Amati modelis - Pārstāv amata (darba vietas) ierakstus datu bāzē
 * 
 * Amati ir darba pozīcijas klasifikators sistēmā (piemēram: "Admins", "Darbinieks", "Klients")
 * Šis modelis tiek lietots, lai noteiktu lietotāju lomas (roles) sistēmā.
 * 
 * Datu bāzes tabula: amats
 * Primārā atslēga: AmataID
 */
class Amati extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'amats';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'AmataID';

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