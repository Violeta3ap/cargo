<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * NomasStatuss modelis - Pārstāv nomas statusa ierakstus
 * 
 * Nomas statuss norāda, kādā stāvoklī atrodas noma (piemēram: "Apstiprināta", "Noraidīta", "Izskatīšanā")
 * Šis modelis tiek izmantots, lai izsekotu nomas gaitu caur dažādajiem stāvļiem
 * 
 * Datu bāzes tabula: NomasStatuss
 * Primārā atslēga: StatusaID
 */
class NomasStatuss extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'NomasStatuss';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'StatusaID';

    /**
     * Vai modelis izmanto created_at un updated_at laikus
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Vai primārā atslēga ir auto-increment (šajā gadījumā nav - manuāli norādīti ID)
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Primārās atslēgas datu tips
     * 
     * @var string
     */
    protected $keyType = 'int';
}
