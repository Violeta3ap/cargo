<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MaksasStatuss modelis - Pārstāv nomas maksas statusu ierakstus
 * 
 * Maksas statuss norāda, vai noma ir apmaksāta vai nav (piemēram: "Apmaksāta", "Nav apmaksāta", "Daļēji apmaksāta")
 * Šis modelis tiek izmantots, lai izsekotu maksājumu statusu katrai nomai
 * 
 * Datu bāzes tabula: MaksasStatuss
 * Primārā atslēga: MaksasID
 */
class MaksasStatuss extends Model
{
    /**
     * Datu bāzes tabulas nosaukums
     * 
     * @var string
     */
    protected $table = 'MaksasStatuss';

    /**
     * Primārās atslēgas kolonna
     * 
     * @var string
     */
    protected $primaryKey = 'MaksasID';

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
