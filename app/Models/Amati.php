<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amati extends Model
{
    protected $table = 'amats'; // Saistīt Eloquent modeli ar datu bāzes tabulu 'amats'
    protected $primaryKey = 'AmataID'; // Norāda primāro atslēgu tabulā
    protected $keyType = 'int'; // Primārās atslēgas tips ir vesels skaitlis
    public $incrementing = true; // Primārā atslēga automātiski palielinās (auto-increment)
    public $timestamps = false; // Nesaglabāt laika zīmogus created_at un updated_at
}